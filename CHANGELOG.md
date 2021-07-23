# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [2.0.6] - 2021-07-23
### Added
- Add code example in README
- Fix SSL ValidationMethod type

## [2.0.5] - 2021-07-14
### Added
- Add infoTypes `pfx`, `der`, `p7b`, `zip`, `pem` to `ssl_getInfo()`
- Add `pfxpass` for generate PFX/PKCS#12 (`infoType` = `pfx`) to `ssl_getInfo()`

### Fixed
- Remove `sanMaxDomains` from `ssl_getInfo()` and `ssl_list()`

## [2.0.4] - 2021-06-30
### Added
- Added  `user_list()` function for api endpoint `/user/list/`
- Added  `user_updateStatus()` function for api endpoint `/user/updatestatus/`
- Added  `user_updatePassowrd()` function for api endpoint `/user/updatepassword/`
- Added  `user_create()` function for api endpoint `/user/create/`
- Added  `user_delete()` function for api endpoint `/user/delete/`
- Added  `user_addDomain()` function for api endpoint `/user/adddomain/`
- Added  `user_updateDomain()` function for api endpoint `/user/updatedomain/`
- Added  `user_deleteDomain()` function for api endpoint `/user/deletedomain/`
- Added  `account_promos()` function for api endpoint `/account/promos/`
- Add `logerror` infoType to `account_promos()`

## [2.0.3] - 2021-06-28
### Added
- Add php infoType in `service_getInfo()`
- Add php updateType & phpversion in `service_update()`
- Add SSL Params to `service_subdomainUpdate()` and `service_subdomainCreate()`
- Added  `contact_create()` function for api endpoint `/contact/create/`

## [2.0.2] - 2021-06-24
### Added
- Added  `ssl_productList()` function for api endpoint `/ssl/productlist/`
- Added  `ssl_productGetInfo()` function for api endpoint `/ssl/productgetinfo/`
- Added  `ssl_list()` function for api endpoint `/ssl/list/`
- Added  `ssl_getInfo()` function for api endpoint `/ssl/getinfo/`
- Added  `ssl_create()` function for api endpoint `/ssl/create/`
- Added  `ssl_getValidationEmails()` function for api endpoint `/ssl/getvalidationemails/`
- Added  `ssl_changeValidationMethod()` function for api endpoint `/ssl/changevalidationmethod/`
- Added  `ssl_renew()` function for api endpoint `/ssl/renew/`
- Added  `ssl_resendValidationMail()` function for api endpoint `/ssl/resendvalidationmail/`
- Added  `ssl_reissue()` function for api endpoint `/ssl/reissue/`
- Added  `ssl_multiDomainAddSan()` function for api endpoint `/ssl/multidomainaddsan/`

## [2.0.1] - 2021-06-15
### Added
- Added  `ssl_csrCreate()` function for api endpoint `/ssl/csrcreate/`
- Added  `ssl_csrDecode()` function for api endpoint `/ssl/csrdecode/`

## [2.0.0] - 2021-01-29
This major release **is not intended to be compatible with version <2.0.0**. Please, check, be sure and test your code if you are upgrading to this version.

### Added
- Added namespaces following PSR-4 conventions.
- Added autoloader.


### Changed
- Examples remade.
- Clean code and better performance in general: innecesary evaluations removed, lazy instantation when needed... etc
- API Info enhanced: now works with throwExceptions = false
- **Breaking change**: \Dondominio\API\Response\Response::success() is now getSuccess();

### Fixed
- Fixed bug when curl failed and exception mode was set to false. cURL behaviour improved in general.
- Output Filters some bugs fixed and improved in general.
- Fixed some evaluations