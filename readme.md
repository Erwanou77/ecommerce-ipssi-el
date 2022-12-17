# Tutoriel installation du projet

### 1. Télécharger le projet en éxecutant la commande suivante

```bash
git clone https://github.com/Erwanou77/ecommerce-ipssi-el.git
```

### 2. Mettre le projet dans votre serveur local www puis lancer votre serveur

### 3. Modifier les variables d'environnement du fichier .env
####    - Modifier votre nom d'utilisateur et votre mot de passe
####    - Modifier votre IP et le port mysql
####    - (Optionnel) modifier le nom de la base de données

### 4. Installer les dépendences de composer

```bash
composer i
```

### 5. Maintenant il faut installer les dépendences nodejs

```bash
npm i
```

### 6. Effectuer la commande pour créer votre base de données

```bash
symfony console d:d:c
```

### 7. Executer la commande pour créer les tables dans la base

```bash
symfony console d:m:m
```

### 8. Effectuer la commande suivante pour build notre application

```bash
npm run watch
```

### 9. Enfin effectuer la commande suivante pour lancer le serveur symfony

```bash
symfony server:start
```