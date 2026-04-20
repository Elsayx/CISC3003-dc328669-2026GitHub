# iSuperviz — Team 07 (CISC3003 Team Project)

Live site: **[Open iSuperviz on GitHub Pages](https://elsayx.github.io/CISC3003-dc328669-2026GitHub/CISC3003-TeamProject-Team07-iSuperviz/)**

This folder contains the **production build** of the Team 07 project —
`iSuperviz: Your AI Research Supervisor`. It is a full-stack web application
(React 18 + TypeScript + Ant Design + React-Flow frontend, Node.js + Express +
SQLite backend) that has been compiled for static hosting on GitHub Pages.

## How this works on GitHub Pages

GitHub Pages is static hosting, so the real Node.js backend can't run here.
To keep the experience **identical to the local full-stack version**, the SPA
ships with a client-side **mock adapter** (`src/mock/`) that:

- Intercepts every `/api/*` request via an axios interceptor
- Runs the same business logic (auth, cart, orders, search, ideas, chat, …)
  against an in-memory DB persisted to `localStorage`
- Auto-activates whenever the site is served from a `*.github.io` host
- Seeds realistic demo data (25 arXiv papers with excerpts, 15 products, 4
  demo ideas) so the graph is never empty — even for guests

Everything the reviewer can do on localhost works here:
signup / email verify / login / password reset / dashboard /
search / history / cart / checkout / AI chat / idea graph /
hallucination audit / redemption codes.

## Quick-start for reviewers

Open the live URL above and either:

1. **Use the demo account**
   - Email: `professor@um.edu.mo`
   - Password: `demo1234`
   - Redemption code: `TEAM07-ABC` (+100 credits)
2. **Sign up with any email + 6-char password** — the signup flow is
   captcha-optional for the reviewer experience.

## Team 07

| Pair    | Role               | Student ID | Name         |
| ------- | ------------------ | ---------- | ------------ |
| Pair 08 | Member (Team Lead) | DC328669   | Yang Xu      |
| Pair 08 | Partner            | DC328023   | Jiang Xingyu |
| Pair 12 | Member             | DC326312   | Huang Sofia  |
| Pair 12 | Partner            | DC326351   | Fan Zou Chen |
| Pair 04 | Member             | DC227126   | Si Tin Iek   |
| Pair 04 | Partner            | DC226328   | Ma Iat Tim   |

## Re-deploy

The source lives outside this folder. To rebuild and redeploy:

```bash
cd <iSuperviz-source>
REACT_APP_FORCE_MOCK=1 CI=false npm run build
rm -rf <this-folder>/*
cp -R build/. <this-folder>/
touch <this-folder>/.nojekyll
git add . && git commit -m "redeploy iSuperviz" && git push
```

Course: CISC3003 Project Assignment 2026APR13 — 2026MAY04.
