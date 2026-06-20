#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
通用数据清洗工具
支持 CSV / Excel 格式，涵盖常见清洗操作。
"""

import pandas as pd
import numpy as np
import os
from pathlib import Path


def load_data(file_path: str) -> pd.DataFrame:
    """根据扩展名自动加载数据文件"""
    ext = Path(file_path).suffix.lower()
    if ext == '.csv':
        return pd.read_csv(file_path)
    elif ext in ('.xlsx', '.xls'):
        return pd.read_excel(file_path)
    elif ext == '.json':
        return pd.read_json(file_path)
    elif ext == '.tsv':
        return pd.read_csv(file_path, sep='\t')
    else:
        raise ValueError(f"不支持的文件格式: {ext}")


def inspect_data(df: pd.DataFrame) -> dict:
    """数据概览——输出基本信息"""
    info = {
        'shape': df.shape,
        'columns': list(df.columns),
        'dtypes': df.dtypes.to_dict(),
        'missing_count': df.isnull().sum().to_dict(),
        'missing_pct': (df.isnull().sum() / len(df) * 100).to_dict(),
        'duplicate_rows': df.duplicated().sum(),
        'unique_counts': {col: df[col].nunique() for col in df.columns},
    }
    return info


def drop_duplicates(df: pd.DataFrame, subset: list = None,
                    keep: str = 'first') -> pd.DataFrame:
    """删除重复行"""
    before = len(df)
    df = df.drop_duplicates(subset=subset, keep=keep)
    print(f"删除了 {before - len(df)} 行重复数据")
    return df


def handle_missing_values(
    df: pd.DataFrame,
    strategy: str = 'auto',
    fill_value_map: dict = None,
    thresh: float = 0.5,
) -> pd.DataFrame:
    """
    处理缺失值

    参数:
        strategy: 'drop' 删除含缺失的行
                  'drop_col' 删除缺失率超过 thresh 的列
                  'fill_mean' / 'fill_median' / 'fill_mode' 用统计值填充
                  'auto' 数值列用中位数，类别列用众数
        fill_value_map: 指定列的自定义填充值 {列名: 填充值}
        thresh: 列缺失率阈值（仅 drop_col 时使用）
    """
    if fill_value_map:
        df = df.fillna(fill_value_map)
        return df

    if strategy == 'drop':
        before = len(df)
        df = df.dropna()
        print(f"删除了 {before - len(df)} 行含缺失值的数据")
        return df

    if strategy == 'drop_col':
        before = df.shape[1]
        cols_to_drop = [c for c in df.columns
                        if df[c].isnull().mean() > thresh]
        df = df.drop(columns=cols_to_drop)
        print(f"删除了 {len(cols_to_drop)} 列 (缺失率 > {thresh:.0%})")
        return df

    if strategy == 'auto':
        for col in df.columns:
            if df[col].isnull().sum() == 0:
                continue
            if pd.api.types.is_numeric_dtype(df[col]):
                df[col].fillna(df[col].median(), inplace=True)
            else:
                df[col].fillna(df[col].mode().iloc[0]
                               if not df[col].mode().empty else '未知',
                               inplace=True)
        print("自动填充完成（数值→中位数，类别→众数）")
        return df

    if strategy == 'fill_mean':
        num_cols = df.select_dtypes(include=np.number).columns
        df[num_cols] = df[num_cols].fillna(df[num_cols].mean())
        return df

    if strategy == 'fill_median':
        num_cols = df.select_dtypes(include=np.number).columns
        df[num_cols] = df[num_cols].fillna(df[num_cols].median())
        return df

    if strategy == 'fill_mode':
        for col in df.columns:
            mode_val = df[col].mode()
            if not mode_val.empty:
                df[col].fillna(mode_val.iloc[0], inplace=True)
        return df

    return df


def clean_text_columns(df: pd.DataFrame, columns: list = None) -> pd.DataFrame:
    """清洗文本列：去除首尾空格、统一空白、统一小写"""
    if columns is None:
        columns = df.select_dtypes(include='object').columns.tolist()
    for col in columns:
        if col in df.columns and df[col].dtype == 'object':
            df[col] = df[col].str.strip()                     # 去首尾空格
            df[col] = df[col].str.replace(r'\s+', ' ', regex=True)  # 合并连续空白
            # 可选：统一为小写（可注释掉）
            # df[col] = df[col].str.lower()
    print(f"已清洗文本列: {columns}")
    return df


def convert_dtypes_auto(df: pd.DataFrame) -> pd.DataFrame:
    """自动优化数据类型（节省内存）"""
    for col in df.columns:
        # 尝试转为数字
        if df[col].dtype == 'object':
            try:
                df[col] = pd.to_numeric(df[col])
            except (ValueError, TypeError):
                pass
    # 对数值列向下转型
    int_cols = df.select_dtypes(include='integer').columns
    df[int_cols] = df[int_cols].apply(pd.to_numeric, downcast='integer')

    float_cols = df.select_dtypes(include='float').columns
    df[float_cols] = df[float_cols].apply(pd.to_numeric, downcast='float')
    return df


def detect_outliers_iqr(df: pd.DataFrame, columns: list = None,
                        factor: float = 1.5) -> dict:
    """基于 IQR 检测异常值，返回异常行索引"""
    if columns is None:
        columns = df.select_dtypes(include=np.number).columns.tolist()
    outliers = {}
    for col in columns:
        Q1 = df[col].quantile(0.25)
        Q3 = df[col].quantile(0.75)
        IQR = Q3 - Q1
        lower = Q1 - factor * IQR
        upper = Q3 + factor * IQR
        mask = (df[col] < lower) | (df[col] > upper)
        outliers[col] = {
            'count': mask.sum(),
            'lower_bound': lower,
            'upper_bound': upper,
            'indices': df.index[mask].tolist(),
        }
    return outliers


def remove_outliers_iqr(df: pd.DataFrame, columns: list = None,
                        factor: float = 1.5) -> pd.DataFrame:
    """删除 IQR 异常值所在行"""
    if columns is None:
        columns = df.select_dtypes(include=np.number).columns.tolist()
    mask = pd.Series(False, index=df.index)
    for col in columns:
        Q1 = df[col].quantile(0.25)
        Q3 = df[col].quantile(0.75)
        IQR = Q3 - Q1
        lower = Q1 - factor * IQR
        upper = Q3 + factor * IQR
        mask |= (df[col] < lower) | (df[col] > upper)
    before = len(df)
    df = df[~mask]
    print(f"基于 IQR 删除了 {before - len(df)} 行异常值")
    return df


def save_data(df: pd.DataFrame, output_path: str) -> None:
    """保存清洗后的数据"""
    ext = Path(output_path).suffix.lower()
    os.makedirs(Path(output_path).parent, exist_ok=True)
    if ext == '.csv':
        df.to_csv(output_path, index=False, encoding='utf-8-sig')
    elif ext in ('.xlsx', '.xls'):
        df.to_excel(output_path, index=False)
    elif ext == '.json':
        df.to_json(output_path, orient='records', force_ascii=False)
    else:
        raise ValueError(f"不支持的输出格式: {ext}")
    print(f"数据已保存至: {output_path}")


# ──────────────────────────────────────────────
# 使用示例
# ──────────────────────────────────────────────
if __name__ == '__main__':
    # ====== 请根据实际情况修改以下路径 ======
    INPUT_FILE = 'data/原始数据.csv'
    OUTPUT_FILE = 'data/清洗后数据.csv'
    # =========================================

    # 1. 加载数据
    print(">>> 加载数据...")
    df = load_data(INPUT_FILE)

    # 2. 查看概览
    info = inspect_data(df)
    print(f"\n数据形状: {info['shape']}")
    print(f"缺失值统计:\n{pd.DataFrame(info['missing_count'], index=['缺失数']).T}")
    print(f"重复行数: {info['duplicate_rows']}\n")

    # 3. 清洗流程
    df = drop_duplicates(df)
    df = clean_text_columns(df)
    df = handle_missing_values(df, strategy='auto')
    df = convert_dtypes_auto(df)
    df = remove_outliers_iqr(df)

    # 4. 保存结果
    save_data(df, OUTPUT_FILE)
    print(">>> 清洗完成！")
