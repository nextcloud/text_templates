# Change Log
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased]

## 1.0.5 – 2024-03-14

### Changed
* Update node deps (mainly nc/vue8) @kyteinsky

### Fixed
* Cleanup use of $config property in OCA\TextTemplates\AppInfo\Application @tcitworld
* Composer fixes @kyteinsky

### Added
* Create pr-feedback.yml @marcelklehr
* Add and use phpunit as composer dependency @julien-nc
* Add controller unit tests @MB-Finski
* Compatibility for NC 29 @kyteinsky

## 1.0.4 – 2023-06-29

### Changed

- Move personal settings to 'additional settings' section @nickvergessen [#6](https://github.com/nextcloud/text_templates/pull/6)

## 1.0.3 – 2023-06-28

### Changed

- Add padding to picker component
- Replace frontend OC functions with ones from packages

### Fixed

- Avoid showing 'add' button twice in picker

## 1.0.1 – 2023-03-10
### Changed
- use link picker stuff from @nextcloud/vue
- add perso settings empty content when there is no admin templates

### Fixed
- make picker search case insensitive
- break lines in picker result content

## 1.0.0 – 2023-03-06
### Added
* the app
