# Neos.Rector

This package contains a Rector ruleset which is used for migrating from Neos 8.x to 9.0 (and possibly also further up).

It will eventually replace Core/Code Migrations (./flow flow:core:migrate), but right now it is still in Development.

Right now we focus especially on rules to migrate from the old Content Repository API (< Neos 9.0) to the
Event Sourced Content Repository (>= 9.0).

## Installation

```bash
# inside your Distribution folder
composer require --dev neos/rector:dev-main
cp Packages/Libraries/neos/rector/rector.template.php rector.php
```

## Configuration

Now, open up the `rector.php` file copied above, and adjust the Rector Paths (these are the paths which shall be
migrated). By default, all of `./DistributionPackages` will be migrated.

Right now, we ship the following sets of Rector rules:

- `\Neos\Rector\NeosRectorSets::CONTENTREPOSITORY_9_0`: all rules needed to migrate to the Event-Sourced Content Repository
  (currently still in progress)

## Running

Run the following command at the root of your distribution (i.e. where `rector.php` is located).

```bash
# for trying out what would be done
./bin/rector --dry-run

# for running the migrations
./bin/rector
```

# Developing Rector Rules for Neos

(This section is not relevant for users, but for **developers** of the Neos Rector packages)

## Running Tests

We develop **all** Rector Rules completely test-driven.

The test setup runs completely local; does not need *any* Distribution set up.

```bash
# if inside a Neos Distribution, change to the Package's folder
cd Packages/Libraries/neos/rector

# install PHPunit 
composer install

# run PHPUnit
composer test
```

## Fusion Rector

We extended Rector specifically for migrating Fusion files, by providing a `FusionFileProcessor` and a `FusionRectorInterface`
which you can implement if you want to build Fusion transformations.

The Fusion Rectors will usually use one of the following tooling classes:

- `EelExpressionTransformer`: for finding all Eel expressions inside Fusion and AFX; and transforming them in some way.
- (more tooling classes to come here as we need them).

The Fusion and AFX Parsing functionality is based on the official Fusion and AFX parsers. However, the classes are
vendored/copied into this package by the `./embed-fusion-and-afx-parsers.sh` script, because of the following reasons:

- Rector needs to run even when Flow cannot compile the classes; so we cannot depend on a Flow package.
- We slightly need to patch the AFX parser, because we need position information for Eel Expressions.

The Fusion parser was subclassed by `Neos\Rector\Core\FusionProcessing\CustomObjectTreeParser` for retaining position
information of AFX and Eel Expressions.


**Updating Fusion and AFX Parser**

To update the vendored Fusion and AFX parsers, run the `./embed-fusion-and-afx-parsers.sh` script.


**Updating the AFX Parser Patch**

The AFX parser needs a custom patch (see `./scripts/afx-eel-positions.patch`) to retain positions.

To create/update this patch, do the following:

```bash
cd Packages/Neos

# apply the current patch
patch -p1 < ../Libraries/neos/rector/scripts/afx-eel-positions.patch

# Now, do your modifications as needed.

# when you are finished, create the new patch 
git diff -- Neos.Fusion.Afx/ > ../Libraries/neos/rector/scripts/afx-eel-positions.patch

# ... and reset the code changes inside Neos.Fusion.Afx.
git restore -- Neos.Fusion.Afx/
```

