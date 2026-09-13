# Devia Technologic — Plateforme Numérique de Gestion des Requêtes

Plateforme web responsive et moderne de dépôt, d'instruction, de suivi et de validation des requêtes académiques et administratives pour **Devia Technologic**.

---

## 🔑 Accès Administrateur Système (Pre-configured Admin Credentials)

- **Identifiant / Email** : `admin@deviatech.com` *(ou Matricule `DEV-SYS-001`)*
- **Rôle** : Administrateur Système
- **Mot de passe** : `admin123` (ou n'importe quel mot de passe saisi)

---

## 🛠️ Inscription des Utilisateurs & Gestion des Rôles (RBAC)

1. **Demandeur / Étudiant** : Accès immédiat après inscription.
2. **Personnel Staff / Gestionnaire / Resp. Pédagogique** : Enregistrement soumis à la validation préalable de l'**Administrateur Système**.
3. **Changement de Rôle** : L'Administrateur Système peut promouvoir ou réattribuer n'importe quel rôle à tout moment depuis l'**Annuaire & Rôles**.

---

## 🚀 Déploiement sur GitHub & Vercel

### 1. Publier sur GitHub

```bash
git init
git add .
git commit -m "Initial commit - Devia Technologic Requests App"
git branch -M main
git remote add origin https://github.com/votre-compte/devia-technologic-app.git
git push -u origin main
```

### 2. Déployer sur Vercel

1. Connectez-vous sur [Vercel.com](https://vercel.com).
2. Cliquez sur **"Add New"** > **"Project"**.
3. Importez votre dépôt GitHub `devia-technologic-app`.
4. Laissez les paramètres par défaut (`Framework Preset: Vite`, `Build Command: npm run build`, `Output Directory: dist`).
5. Cliquez sur **"Deploy"**.

---

## 💻 Exécution en Local

```bash
npm install
npm run dev
```

Serveur disponible sur `http://localhost:3000/`.
