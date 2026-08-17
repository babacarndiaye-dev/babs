# École Professionnelle — Plateforme Web & ERP
## Document de Phase 1 : Architecture, Conception & Feuille de route

> Ce document répond aux 12 livrables demandés en préalable au développement.
> Aucun code applicatif n'est produit à ce stade — validation attendue avant la Phase 5 (implémentation).

---

## 1. Architecture produit globale

L'application est un **ERP scolaire + site vitrine + admissions en ligne**, construit comme un **monolithe modulaire Laravel**, mono-établissement dans sa version 1, mais structuré pour devenir multi-établissement sans réécriture.

```
┌─────────────────────────────────────────────────────────────────┐
│                         COUCHE PUBLIQUE                          │
│  Site vitrine (SEO) · Catalogue formations · Candidature en ligne │
│  Vérification de documents (QR) · Actualités · Galerie · Contact │
└───────────────────────────────┬───────────────────────────────────┘
                                 │
┌────────────────────────────────▼───────────────────────────────┐
│                    COUCHE AUTHENTIFICATION                      │
│   Multi-guards : candidat · étudiant · enseignant · staff/admin  │
│   Rôles & permissions configurables (Spatie Permission)          │
└───────────────────────────────┬───────────────────────────────┘
                                 │
        ┌────────────────────────┼────────────────────────┐
        ▼                        ▼                          ▼
┌───────────────┐      ┌────────────────┐        ┌──────────────────┐
│ ESPACE ÉTUDIANT│      │ ESPACE ENSEIGNANT│      │ ADMINISTRATION ERP │
│ Dashboard, notes│      │ Classes, notes,  │      │ Élèves, finances,  │
│ absences, paiem.│      │ présences, EDT   │      │ académique, config │
└───────────────┘      └────────────────┘        └──────────────────┘
                                 │
┌────────────────────────────────▼───────────────────────────────┐
│                    MOTEUR DE CONFIGURATION                       │
│  Identité · Design · Académique · Finance · Documents · Notifs   │
│  → Tout ce qui est spécifique à l'école est DATA, pas CODE       │
└───────────────────────────────┬───────────────────────────────┘
                                 │
┌────────────────────────────────▼───────────────────────────────┐
│                     SERVICES TRANSVERSAUX                        │
│  Génération PDF · QR Code · File d'attente (jobs) · Stockage     │
│  Notifications (mail/SMS/WhatsApp placeholder) · Audit log        │
└───────────────────────────────────────────────────────────────┘
```

**Principe directeur** : chaque module métier (Formations, Admissions, Étudiants, Finance, Documents…) est un package Laravel interne (`app/Modules/*` ou `packages/`) avec ses propres modèles, migrations, policies, vues Livewire — aucun couplage dur à « l'école X ». Le nom, les couleurs, les formations, les frais, les modèles de documents sont tous résolus au runtime via le module `Settings`.

---

## 2. Carte complète des pages (Page Map)

### 2.1 Site public
| Route | Page |
|---|---|
| `/` | Accueil |
| `/etablissement` | L'Établissement (histoire, mot du directeur, vision, mission, valeurs, équipe, infrastructures) |
| `/etablissement/equipe` | Notre équipe |
| `/etablissement/infrastructures` | Ateliers, labos, salles informatiques |
| `/formations` | Catalogue des formations (avec filtres) |
| `/formations/{slug}` | Détail d'une formation |
| `/admissions` | Processus d'admission (4 étapes) |
| `/admissions/candidater` | Formulaire de candidature |
| `/admissions/candidater/formation/{slug}` | Candidature pré-remplie pour une formation |
| `/candidat/suivi` | Suivi de candidature (numéro de dossier) |
| `/actualites` | Liste des actualités/événements |
| `/actualites/{slug}` | Détail actualité |
| `/galerie` | Galerie photo/vidéo par catégorie |
| `/contact` | Contact (formulaire, carte, réseaux sociaux) |
| `/verification-document` | Vérification publique de document (QR/référence) |
| `/mentions-legales`, `/confidentialite` | Pages légales |

### 2.2 Espace candidat
| Route | Page |
|---|---|
| `/candidat/inscription`, `/candidat/connexion` | Auth candidat |
| `/candidat/tableau-de-bord` | Statut de candidature |
| `/candidat/dossier` | Formulaire multi-étapes (infos perso, scolarité, documents) |
| `/candidat/documents` | Upload de pièces |

### 2.3 Espace étudiant (`/etudiant/*`)
Tableau de bord · Mon profil · Ma formation · Emploi du temps · Notes · Absences · Paiements · Reçus · Documents · Stages · Notifications

### 2.4 Espace enseignant (`/enseignant/*`)
Tableau de bord · Mes classes · Mes matières · Emploi du temps · Mes étudiants · Présences · Évaluations · Notes · Documents

### 2.5 Administration (`/admin/*`)
Tableau de bord (KPI) · Candidatures · Étudiants · Enseignants · Classes · Matières · Emplois du temps · Présences · Évaluations & Notes · Bulletins · Finance (frais, factures, paiements, reçus) · Stages & Entreprises · Documents & Modèles · Actualités/Événements/Galerie (CMS) · Notifications · Utilisateurs & Rôles · **Paramètres de l'établissement** · Journal d'audit

---

## 3. Structure de navigation

**Navbar publique** : Accueil · L'Établissement · Formations · Admissions · Actualités · Galerie · Contact — CTA `Candidater maintenant` — liens secondaires `Espace étudiant` / `Espace enseignant` / `Administration`.

**Sidebar Étudiant** : Tableau de bord, Mon profil, Ma formation, Emploi du temps, Notes, Absences, Paiements, Reçus, Documents, Stages, Notifications.

**Sidebar Enseignant** : Tableau de bord, Mes classes, Mes matières, Emploi du temps, Mes étudiants, Présences, Évaluations, Notes, Documents.

**Sidebar Admin** (groupée par section, repliable) :
- *Pilotage* : Dashboard, Rapports
- *Recrutement* : Candidatures, Candidats
- *Académique* : Formations, Classes, Matières, Enseignants, Étudiants, Emplois du temps, Présences, Évaluations, Bulletins
- *Finance* : Frais, Factures, Paiements, Reçus, Échéanciers
- *Stages* : Entreprises, Conventions
- *Communication* : Actualités, Événements, Galerie, Notifications
- *Documents* : Modèles, Vérification
- *Système* : Utilisateurs, Rôles & permissions, Paramètres, Journal d'audit

Toutes les sidebars sont **générées dynamiquement à partir des permissions** de l'utilisateur (menu piloté par policy/gate, pas de menu en dur par rôle).

---

## 4. Concept UX/UI

- **Style** : SaaS éducatif premium — sobre, aéré, orienté données pour les portails ; chaleureux et photographique pour le site public.
- **Design tokens configurables** (stockés en base, exposés en CSS custom properties générées server-side) :
  - `--color-primary` (défaut vert profond), `--color-accent` (défaut orange), `--color-bg`, `--color-surface`, `--color-text`
  - Typo : une police sans-serif système + une police d'affichage pour les titres, tailles en échelle modulaire
  - Rayon de bordure, ombre, espacement — variables réutilisables (Tailwind config lit ces tokens au build, ou CSS vars injectées pour un rebrand sans rebuild)
- **Composants réutilisables** (Livewire + Blade + Tailwind) : Card formation, KPI card, DataTable (tri/filtre/pagination), Badge de statut, Stepper (admission, candidature), Modal, Toast, Skeleton loader, Empty state, Sidebar responsive, Breadcrumb, Calendrier/EDT, Graphiques (Chart.js ou ApexCharts).
- **Mobile-first** impératif pour le portail étudiant (paiements, notes, EDT consultés majoritairement sur mobile).
- **Accessibilité** : contrastes AA, focus visibles, aria-labels sur composants interactifs.
- **Photographie** : banque d'images configurable par section (hero, formation, établissement) via le module Médiathèque, jamais codée en dur.

---

## 5. Schéma de base de données (entités principales)

Base relationnelle normalisée (MySQL 8 / PostgreSQL). Tables clés par domaine :

**Identité & accès**
`users`, `roles`, `permissions`, `role_has_permissions`, `model_has_roles`, `password_resets`, `personal_access_tokens`

**Configuration**
`settings` (clé/valeur typée + groupe), `document_templates`, `notification_templates`, `academic_years`

**Académique — référentiel**
`formation_types`, `domains`, `formations`, `specialties`, `levels`, `subjects`, `formation_subjects` (pivot + coefficient), `grading_systems`, `grading_scales`, `rooms`

**Académique — organisation**
`classes` (classe = formation + niveau + année), `class_subject_teacher` (affectations), `schedules` (créneaux EDT)

**Personnes**
`teachers`, `teacher_qualifications`, `students`, `student_class` (historique inscriptions par année), `candidates`

**Admissions**
`applications`, `application_documents`, `application_status_history`

**Vie académique**
`attendances`, `assessments`, `grades`, `report_cards`, `report_card_lines`

**Finance**
`fee_types`, `fee_schedules`, `invoices`, `invoice_lines`, `payments`, `receipts`, `payment_methods`

**Stages**
`companies`, `company_contacts`, `internships`, `internship_evaluations`

**Documents**
`documents` (générés, avec référence + QR), `document_templates`

**Communication**
`news`, `events`, `gallery_items`, `gallery_categories`, `notifications`, `contact_messages`

**Système**
`audit_logs`, `media` (Spatie Media Library)

> Chaque table métier porte une **clé conceptuelle de rattachement établissement** (`school_id`, nullable/valeur unique en v1) pour préparer le multi-tenant futur sans migration structurelle lourde.

---

## 6. Relations entre entités (extrait)

```
users 1—1 students / teachers / candidates (polymorphe via profil)
academic_years 1—N classes, applications, invoices
formations 1—N specialties, classes ; N—N subjects (via formation_subjects)
classes 1—N students (via student_class), 1—N schedules
schedules N—1 subjects, teachers, rooms, classes
students 1—N attendances, grades, invoices, internships, documents
assessments N—1 subject, class ; 1—N grades
grades N—1 student, assessment → agrégées en report_cards
invoices 1—N invoice_lines, payments → 1—1 receipts par paiement
applications N—1 candidate, formation → 1 étudiant si admis (conversion)
internships N—1 student, company
documents N—1 document_templates, polymorphe vers student/candidate
```

Diagramme ER détaillé (Mermaid) à livrer en Phase 3 avec le schéma de migrations Laravel définitif.

---

## 7. Rôles utilisateurs & permissions

| Rôle | Portée |
|---|---|
| **Super Administrateur** | Accès total, gestion multi-établissement future |
| **Directeur** | Dashboards stratégiques, rapports, validation de décisions |
| **Administrateur d'établissement** | Gestion complète de l'établissement courant |
| **Responsable académique** | Formations, classes, matières, EDT, bulletins |
| **Chargé des admissions / Scolarité** | Candidatures, inscriptions, dossiers étudiants |
| **Comptable** | Frais, factures, paiements, reçus |
| **Enseignant** | Ses classes : présences, notes, documents |
| **Étudiant** | Ses données académiques et financières (lecture) |
| **Candidat** | Son dossier de candidature uniquement |

Permissions gérées via **Spatie Laravel-Permission** : chaque action (`students.view`, `grades.create`, `invoices.manage`…) est un permission node assignable à un rôle **depuis l'administration**, sans redéploiement. Les rôles ci-dessus sont des presets, pas des contraintes en dur.

---

## 8. Système de configuration (« Paramètres de l'établissement »)

Module central `Settings`, organisé en groupes, piloté par une table `settings(key, value, type, group)` + cache applicatif :

- **Identité** : nom, sigle, logo, slogan, adresse, téléphone, email, WhatsApp, réseaux sociaux
- **Design** : couleurs (primaire/secondaire/accent), logo/favicon, images hero/bannières
- **Académique** : années scolaires actives, types de formation, barèmes de notation (`/20`, `/100`, lettres, personnalisé), coefficients, seuils de réussite, règles de rattrapage
- **Finance** : devise (FCFA fixe), types de frais, échéanciers (comptant/mensuel/trimestriel/personnalisé), méthodes de paiement (interfaces prêtes pour Wave/Orange Money/Free Money/virement/carte — **placeholders**, pas d'intégration simulée)
- **Documents** : modèles (Blade + variables), signatures, tampons, formats de numérotation de référence
- **Notifications** : canaux actifs (email opérationnel dès v1 ; SMS/WhatsApp = interfaces prêtes, à activer plus tard)

Le moteur de rendu (bulletins, reçus, certificats) consomme ces réglages dynamiquement : **aucun template n'a l'identité de l'école codée en dur**.

---

## 9. Cycle de vie de l'étudiant (workflow)

```
Visiteur
   │  consulte le catalogue
   ▼
Candidat  ──(crée un compte, choisit une formation)
   │
   ▼
Dossier de candidature
   │  Nouveau → En étude → (Incomplet → Pièces complémentaires demandées) ⇄
   │                     → Admis | Refusé
   ▼
Admis
   │  conversion en compte Étudiant + affectation à une classe
   ▼
Inscrit (statut applications → "Inscrit")
   │
   ▼
Étudiant actif
   ├── Vie académique : présences, évaluations, notes, bulletins
   ├── Vie financière : facturation, paiements, reçus
   ├── Stage : convention, évaluation, note de stage
   └── Documents : attestations, certificats, bulletins, carte étudiant
   │
   ▼
Fin de formation
   │  Réussite → Certificat de fin de formation + relevé final
   │  Échec/Abandon → statut archivé
   ▼
Ancien étudiant (alumni — hors périmètre v1, prévu en base)
```

Chaque transition écrit une entrée dans `application_status_history` / `audit_logs` pour traçabilité.

---

## 10. Architecture technique

- **Framework** : Laravel 11 (PHP 8.3+), architecture modulaire (`app/Domain/{Module}` avec Models, Services, Policies ; `app/Livewire` pour les composants UI)
- **Frontend** : Blade + Livewire 3 + Alpine.js (interactions légères) + Tailwind CSS (design tokens dynamiques)
- **Base de données** : MySQL 8 (ou PostgreSQL 15), migrations versionnées, seeders de démo
- **Auth** : Laravel Breeze/Fortify adapté multi-guards (`web` staff/admin, `student`, `teacher`, `candidate`) + Spatie Permission
- **Fichiers & médias** : Spatie Media Library, stockage S3-compatible (ou local en dev)
- **PDF** : `barryvdh/laravel-dompdf` ou `spatie/browsershot` pour bulletins/reçus/attestations à partir de templates Blade configurables
- **QR Code** : `simplesoftwareio/simple-qrcode`, référence unique signée (hash) par document pour vérification publique
- **Files d'attente** : Laravel Queue (database ou Redis) pour PDF, notifications, imports
- **Notifications** : Laravel Notification (canaux `mail` actif ; classes `WhatsAppChannel`/`SmsChannel` en interface, implémentation différée)
- **Cache** : cache des `settings` et du catalogue public (Redis si disponible, sinon file)
- **Tests** : Pest/PHPUnit — Feature tests par module, tests de policies
- **Sécurité** : policies Laravel par ressource, form requests avec validation stricte, CSRF natif, rate limiting sur auth/candidature, validation stricte des uploads (type MIME, taille, scan), logs d'audit sur actions sensibles

---

## 11. Structure de dossiers du projet

```
app/
├── Domain/
│   ├── Settings/         (Models, Services, Livewire\Settings\*)
│   ├── Formations/
│   ├── Admissions/
│   ├── Students/
│   ├── Teachers/
│   ├── Academics/        (classes, matières, EDT, présences, évaluations, bulletins)
│   ├── Finance/
│   ├── Internships/
│   ├── Documents/
│   └── Communication/    (news, events, gallery)
├── Livewire/
│   ├── Public/           (Home, TrainingCatalog, TrainingDetail, ApplicationWizard...)
│   ├── Student/
│   ├── Teacher/
│   └── Admin/
├── Models/                (ou co-localisés dans Domain/*/Models)
├── Policies/
├── Notifications/
│   └── Channels/          (WhatsAppChannel, SmsChannel — placeholders)
├── Services/
│   ├── PdfGenerator.php
│   ├── QrVerificationService.php
│   └── SettingsResolver.php
└── Http/Middleware/

resources/
├── views/
│   ├── components/        (design system : card, badge, stepper, kpi...)
│   ├── layouts/           (public, student, teacher, admin)
│   └── livewire/
├── css/ (tokens Tailwind)
└── js/

database/
├── migrations/
├── seeders/                (DemoSchoolSeeder — données de l'école pilote)
└── factories/

routes/
├── web.php
├── student.php
├── teacher.php
└── admin.php

tests/
├── Feature/{Domain}/
└── Unit/
```

---

## 12. Feuille de route de développement

| Phase | Contenu | Statut |
|---|---|---|
| 1 | Analyse des besoins (ce document) | ✅ Fait |
| 2 | Conception UX/UI (maquettes, design system) | ✅ Fait (design system Tailwind intégré directement en construisant) |
| 3 | Conception base de données (migrations + ERD final) | ✅ Fait (45 tables, ~35 modèles) |
| 4 | Mise en place architecture (skeleton Laravel, CI, environnements) | ✅ Fait |
| 5 | Authentification & rôles (Spatie Permission, guard unique + rôles) | ✅ Fait |
| 6 | Paramètres de l'établissement | ✅ Fait |
| 7 | Formations (catalogue public + gestion admin) | ✅ Fait |
| 8 | Admissions (candidature en ligne + suivi + gestion admin) | ✅ Fait |
| 9 | Étudiants (profils, portail) | À venir |
| 10 | Enseignants / Classes / Matières | À venir |
| 11 | Emploi du temps & présences | À venir |
| 12 | Évaluations & notes | À venir |
| 13 | Bulletins (PDF configurables) | À venir |
| 14 | Finance (frais, factures, paiements, reçus, échéanciers) | À venir |
| 15 | Documents & vérification QR | À venir |
| 16 | Actualités, événements, galerie (CMS) | À venir |
| 17 | Notifications | À venir |
| 18 | Tests & sécurité (audit, durcissement) | À venir |

Chaque phase = une itération livrable et testable indépendamment, avec seed de données de démonstration pour l'école pilote.

---

## Points de validation avant la Phase 2

1. Confirmer la stack **Laravel + Blade/Livewire + Tailwind + MySQL** (vs. alternative Next.js) ✅ par défaut selon le brief.
2. Confirmer le nom, le sigle et les couleurs par défaut de l'école pilote (à défaut, valeurs génériques configurables seront utilisées en seed).
3. Confirmer la liste des formations réelles à intégrer en données de démonstration (CAP, BEP, BT, BTS, modulaires — exemples ou données réelles ?).
4. Confirmer si un hébergement/environnement cible est déjà choisi (impacte le choix S3 vs stockage local, Redis vs file cache).

Une fois ces points validés, le développement démarre à la **Phase 2** module par module.
