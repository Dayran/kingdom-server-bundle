# Kingdom server

Bundle allows creation of kingdom like server based on Symfony 3.*.

Purely a hobby project.

[![Build Status](https://travis-ci.org/7thcubic/kingdom-server-bundle.svg?branch=dev)](https://travis-ci.org/7thcubic/kingdom-server-bundle)

> Does not contain any client nor will i support client development

## Important notes

The accounts in this bundle is Game Account, please develop your own user/lobby account or use any other 3rd party symfony user bundle (eg. FOS User);

Because there is no lobby account logic in this bundle there will be no sitter/dual logic in this bundle  

## Feature List

 * [x] Set up
    - [x] Generators
    - [x] Command
 * Server
    - [x] Multiple server listening on different domain
    - [x] Game Accounts 
    - [x] Server rules
        - [x] Build rule
        - [X] Attack rule
        - [x] Effect rule
        - [x] Influence rule
    - [x] Events
    - [x] Chat
    - [x] Building
    - [x] Attack
       - [x] Unit
         - [x] Stats
         - [ ] Training
       - [x] Battle
         - [x] Send Hero
         - [x] Send Troops
    - [ ] Quest
       - [x] Steps       
       - [ ] Awards
       - [x] Log
    - [ ] Avatar
       - [ ] Stats
       - [x] Look
       - [ ] Inventory
    - [x] Consumables
    - [ ] Treasures
    - [ ] Kingdom
        - [ ] Influence
        - [ ] Kings and Governors
 * [ ] Tests
 
## Installation

### Install the package via composer

Add the repository to composer.json after creating a new symfony 3.* project

```yaml
    "repositories" : [{
        "type" : "vcs",
        "url" : "https://github.com/7thcubic/kingdom-server-bundle"
    }],
```
```
composer require kori\kingdom-server-bundle 
```
### Add the package to the kernel
```php
   $bundles = [
        new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
        ...
        new Kori\KingdomServerBundle\KoriKingdomServerBundle()
   ];
```

### Configure the server
```yaml
kori_kingdom_server:
    servers:
      my_server:
        domain: game.domain.com
        db_connection: "@doctrine.orm.default_entity_manager" # Provide a different entity manager for each instance
        rate: 1 # Server speed
        days_of_protection: 7 # Protection period
        
```

### Set up the server

Run the following command to set up the server.

```
php bin/console kingdom:setup

# Example 
php bin/console kingdom:setup -g CustomWorld -g NPC
```

Optional Parameters:
* --override/-o: Overrides the current world and run set up again
* --generator/-g: Generators to use
* --ignore_restrictions/-ignore: Ignores the generator requirements

> Note: There is a strict requirement of providing a world, race, building, technology and units generator.

### Creating custom generators

* All generators must implement the GeneratorInterface. 
* There are no restrictions on types.
* Generators runs from smallest to largest, 0 -> ...
* Existing types are:
  * World: 0
  * Race: 1
  * Building: 2
  * Technology: 3
  * Units: 4
  * NPC: 5
  * Quest: 6
  * Consumables: 7

## Rules

Rule systems are required for logic handling, it allows for expending of current rule set.

### Assigning rules

If no rules are defined for the server, the default rule set would be used.

While there can be multiple build rule set, there can only be 1 attack rule per server.

```yaml
kori_kingdom_server:
    default_rules:
      build: [basic, additional1, ... ]
      attack: standard
      influence: basic
```

To define rules to use for a particular server

```yaml
kori_kingdom_server:
    servers:
      my_server:
       ...
       build: [basic]
       attack: weakbuilding
       influence: basic
```

### Creating Rules

You may create and assign different rule set to the different servers.

#### Build Rules

Build rule is used to process and validate a valid build request.

To register a build rule add the tag name "kori_kingdom.build_rule" to the service

```yaml
# Example
services:
  Kori\KingdomServerBundle\Rules\Build\:
    resource: '../../Rules/Build/*'
    tags:
      - { name: kori_kingdom.build_rule }
```

> All Build rules must implement the BuildRuleInterface

#### Attack Rule

Attack rule is used to process the result of a fight between two towns.

To register an attack rule add the tag name "kori_kingdom.attack_rule" to the service

```yaml
services:
  Kori\KingdomServerBundle\Rules\Attack\:
    resource: '../../Rules/Attack/*'
    tags:
      - { name: kori_kingdom.attack_rule }
```

> All attack rule must implement the AttackRuleInterface

#### Effect Rule

Effect rule is used to process the effects of consuming an item.

To register an attack rule add the tag name "kori_kingdom.effect_rule" to the service

```yaml
# Example
services:
  Kori\KingdomServerBundle\Rules\Effects\:
    resource: '../../Rules/Effects/*'
    tags:
      - { name: kori_kingdom.effect_rule }
```

> All effect rule must implement the EffectRuleInterface

#### Influence Rule

Influence rule is used to determine the strength factor of influence from the town to determine the spread of kingdom

To register an attack rule add the tag name "kori_kingdom.effect_rule" to the service

```yaml
# Example
services:
  Kori\KingdomServerBundle\Rules\Influence\:
    resource: '../../Rules/Influence/*'
    tags:
      - { name: kori_kingdom.influence_rule }
```

> All influence rule must implement the InfluenceRuleInterface

### Events/Activity

Load the standard activities by adding the following to the services.yml

```yaml
imports:
    - { resource: "@KoriKingdomServerBundle/Resources/config/activity.yml" }
```

To register an activity add the tag name "kori_kingdom.activity" to the service

```yaml
#Example
services:
  Kori\KingdomServerBundle\Activity\Standard\:
    resource: '../../Activity/Standard/*'
    tags:
      - { name: kori_kingdom.activity }
```

> All activity must extend Activity

To set a repeating event, set repeatable to true and schedule to the time interval.

Example:

| Repeatable | Schedule  | Result                                        |
|:----------:|:---------:| --------------------------------------------- |
| Yes        | 10        | Run every 10 seconds                          |
| No         | 5000      | Run after 5000 seconds the server is created  |

