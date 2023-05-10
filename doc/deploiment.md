# E18

## route POST : les problèmes de création

validation des données que l'on reçoit

le nom d'un genre doit être entre 5 et 32 caractères
Ajoute une assertion pour controller la restriction

```php
/*
* @Assert\Length(min=5, max=32)
*/
private $name;
```

Dans le controller on utilise le service de validation de contrainte : `ValidatorInterface`

[doc](https://symfony.com/doc/current/validation.html#using-the-validator-service)

La méthode `validate()` nous renvoit un tableau d'erreurs.
Si celui ci n'est pas vide, on renvoit un message d'erreur avec un code d'erreur (400, 422)

```php
$errors = $validatorInterface->validate($genre);
// on regarde si on a des erreurs dans le tableau d'erreurs en sortie de la validation
if (count($errors) > 0) {
    // on renvoit le tableau d'erreurs au format JSON
    // on y ajoute un code HTTP d'erreur : 422 UNPROCESSABLE_ENTITY
    return $this->json(
        // 1. les données
        $errors,
        // 2. le code d'erreur
        Response::HTTP_UNPROCESSABLE_ENTITY
    );
}
```

## route PUT/PATCH

On reçoit l'id de l'objet à modifier par la route
On va chercher cet objet en BDD
Durant la désérialisation, on précise que l'on veux mettre à jour un objet existant

[doc](https://symfony.com/doc/current/components/serializer.html#deserializing-an-object)

```php
$serializerInterface->deserialize(
    // 1. les données de la requete
    $jsonContent, 
    // 2. le type d'objet
    Genre::class,
    // 3. le format des données
    'json',
    // 4. le contexte : l'objet que l'on veux mettre à jour avec les données
    // on modifie le comportement de la deserialisation
    // au lieur de nous créer un nouvel objet
    // il remplit l'objet qu'on lui fournit
    [AbstractNormalizer::OBJECT_TO_POPULATE => $genre]
);
```

On valide que notre objet respecte les validations
On met à jour en BDD

## deserialise des relations

ex : ajouter/modifier un genre à un film

### DoctrineDenormalizer

Cette classe fonctionne comme un voter, c'est à dire qu'elle sera appellée automatiquement par la méthode `deserialize()`

Comme pour les voter, elle a une méthode `supports` qui doit répondre VRAI/FAUX, pour savoir si elle sait faire la déserialisation demandée.

si elle répond VRAI, alors la méthode `denormalize` est exécuté.
Cette méthode doit renvoyer des données, le résultat de la désérialisation.

## route DELETE

Rien de compliqué, on reçoit un ID par la route, on fait un `find` avec le repository, et ensuite un `remove` avec le repository
On renvoit `null` et un `204`

## mise en place de la sécurité : authentification/authorisation

[doc](https://symfony.com/bundles/LexikJWTAuthenticationBundle/current/index.html)

```bash
composer require "lexik/jwt-authentication-bundle"
```

à l'installation il **FAUT ABSOLUMENT** générer les clés de chiffrages
Mais cela n'est fait qu'un seule fois

```bash
bin/console lexik:jwt:generate-keypair
```

la configuration dans le fichier `.env`  et dans le fichier `lexik_jwt_authentication.yaml` sont déjà faites, pas besoin d'y toucher.

On ajoute dans le fichier `security.yaml` dans la partie firewall

```yaml
firewalls:
        login:
            pattern: ^/api/login
            stateless: true
            json_login:
                check_path: /api/login_check
                success_handler: lexik_jwt_authentication.handler.authentication_success
                failure_handler: lexik_jwt_authentication.handler.authentication_failure

        api:
            pattern:   ^/api
            stateless: true
            jwt: ~

```

Puis toujours dans le fichier  `security.yaml` on oublie pas d'ajouter une règle d'access_control pour autoriser tout le monde à accèder aux routes commençant par `/api/login`

```yaml
    # la route pour s'authentifier DOIT être publique
    - { path: ^/api/login, roles: PUBLIC_ACCESS }
```

On en profite pour ajuster le durée de vie du token, dans le fichier `lexik_jwt_authentication.yaml`

```yaml
## en apothésose si pas envie de se prendre la tête
# token_ttl: 6480000 
## dans la vrai vie en DEV
# token_ttl: 64800 # 18h, valable une journée 
## dans la vrai vie en PROD
token_ttl: 3600 # token TTL in seconds, defaults to 1 hour
```

Dernière chose à faire: ajouter la route dans le fichier `routes.yaml`

```yaml
api_login_check:
    path: /api/login_check
```

### Comment ça marche ?

je prend la métaphore du club med, all inclusive.

en arrivant je doit passer à l'acceuil (m'authentifier) et je reçoit un bracelet de couleur (Token JWT)

Maintenant dès que je me présente à une activité du club (une route API), je dois présenter mon bracelet (Token) pour que l'on me reconnaisse (autorisation)

Fin de la métaphore, comment ça marche ?

Pour s'authentifier, on va sur une route: `POST /api/login_check`
Il faut fournir, au format JSON, un login et un mote de passe.
Ce sont les mêmes login/mdp que nous avons mis en place auparavant.

```json
{
    "username":"admin@admin.com",
    "password":"admin"
}
```

en retour je reçoit un token JWT

```json
{
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE2ODE5OTM3MzcsImV4cCI6MTY4MTk5NzMzNywicm9sZXMiOlsiUk9MRV9BRE1JTiJdLCJ1c2VybmFtZSI6ImFkbWluQGFkbWluLmNvbSJ9.gHqrBUIciMSvTeP8KlR6ovwiceLqfTBDL1iZ5cLYX1WueecXeHsmKCdxpGgDuOcOlcEgYQCNJL4uAfRbzfhlKofaqruFAkNX2Qz_OX36I2WPm50VhTuhXmyB2A4N2B1YJyC0A5iKKuquhvpvgvjfJqWKRaoL7M6xdk-FkVRq7urLb5pwWdy26i04vOJFrNV29uRmG7o3NJWPGsXd-X9hblu2yX5-8ibZiO50ja4OW-SdLP0o-E3QQyU5kdkSdplbK8nN7dDYqjT4rADI8LUip-rD4zYAPYfm2jyZa55sxWhilG9LgKDY2syHrJ_QZaFnph54_FD-NyUWfUFnbnT0Rw"
}
```

Il me suffit de fournir le Token pour m'authenfier automatiquement.
Dans ThunderClient, dans la partie `Auth` on ajoute le token dans la partie `Bearer`

### pour les dev front

Pour des dev front, il faut ajouter une entète HTTP : `Authorization`

Puis comme valeur de cette entète : `Bearer ` (avec l'espace) et le token

```text
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE2Nzg3ODEzMjYsImV4cCI6MTY3ODg0NjEyNiwicm9sZXMiOlsiUk9MRV9BRE1JTiJdLCJ1c2VybmFtZSI6ImFkbWluQGFkbWluLmNvbSJ9.uQPOKIxoi9Eu58RC82tpsaeCZyooEsUHgyugZmkiHiSKM5A5-vJjkVpCL29OmcHWZcQ4QORcrsE5WtGVhPNd3JH7ORvnf6aF4W5zgpEul4K9elwB1L_KRU1ZMP2u7r-dYlmsloIdF9H3M4U7muoVA4hAILCLaZP5e0n2pIfKMMiJGF4lhCjUbPSusRGp_kAC07WCURwqzMwfefSXLNSUg7YSv-bp8xJ4CnoC4bYedFvQYxPRF8ZM0T1Tl0_jyKQPJJlCK2Ji_3d3Uv_edOwoabOfTXvwteZy10c00Q_tg0xiN6zTPddfWiAC_vnRaAsReRxuhOzmKaaRi1fQCWzjEw
```
