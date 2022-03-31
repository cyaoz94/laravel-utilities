# Changelog

All notable changes to `laravel-utilities` will be documented in this file

## 1.0.0 - 2022-03-11

- Initial release

## 1.1 - 2022-03-21

- Add support for Laravel 9

## 1.2 - 2022-03-22

- Update RolePermissionSeeder class to remove permissions that are not defined in `$permissions` array

## 1.3 - 2022-03-22
- Fix CrudController's index method whereby responses are not formatted by the provided `$resourceClass`

## 1.3.1 - 2022-03-22
- Fix RoleController's show API whereby ModelNotFoundException when requesting for a non-existing resource.

## 1.4 - 2022-03-31
- Update QueryFilter class' snakeToCamelCase into a protected function instead of private.

## 1.4.1 - 2022-03-31
- Fix commonOffsetPaginationJsonResponse() whereby `total` returned in response is incorrect. 
