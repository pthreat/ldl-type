# LDL Type Changelog

All changes to this project are documented in this file.

This project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [vx.x.x] - xxxx-xx-xx

### Added

- feature/1200023995006116 - Add ArrayFactoryInterface and ToArrayInterface to validators
- feature/1200017228719580 - Add UniqueStringCollection
- feature/1200005557469693 - Add toArray method to CollectionTrait
- feature/1199958900628846 - Add unshift method
- feature/1199516440696130 - Add implode method to string collection / Add removeByValue method (with optional strict comparison)
- feature/1199718460902669 - MultipleSelectionInterface (and trait) / Improve getSelectedItems method
- feature/1199601516820605 - Add hasSelection to MultipleSelection and SingleSelection (interfaces and traits)
- feature/1199692948932206 - Add ClassStringCollection (Collection of classes as a string with existence validation)
- feature/1200171590526502 - Refactor typed collection to use ldl-validators library

### Changed

- fixes/1200713875634260 - Add getChainItems on each chain from the collections and examples
- fix/1200641806243433 - Fix compare from AmountValidator
- fixes/1200630491660397 - Remove validators config
- fixes/1200577334210951 - Fix validators to comply with ldl-validators (description was moved from config to validators themselves)
- fixes/1200446824922859 - Remove assertFalse from Min and Max validator
- fixes/1199694738432549 - Unique validator shows incorrect message during validatorKey exception
- fixes/1199886097538728 - Remove exception annotation from MultipleSelectionInterface
- fixes/1200949427124330 - Small enhancements to comply with changes done in base

---

## [v0.0.1] - 2020-07-19

### Added

- Initial release, support for basic types and typed object collections

### Changed
