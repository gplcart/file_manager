[![Build Status](https://scrutinizer-ci.com/g/gplcart/file_manager/badges/build.png?b=master)](https://scrutinizer-ci.com/g/gplcart/file_manager/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/gplcart/file_manager/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/gplcart/file_manager/?branch=master)

File manager is a [GPL Cart](https://github.com/gplcart/gplcart) module that allows site administrators to manage files within the system file directory.

**Features**

- Create, move, delete, rename, upload, download, browse, empty files and directories
- Bulk operations
- Control access to paths using regexp patterns
- Control file size of uploaded files
- Control allowed file extensions
- Filter/sort files

**Installation**

1. Download and extract to `system/modules` manually or using composer `composer require gplcart/fm`. IMPORTANT: If you downloaded the module manually, be sure that the name of extracted module folder doesn't contain a branch/version suffix, e.g `-master`. Rename if needed.
2. Go to `admin/module/list` end enable the module
3. Adjust module settings at `admin/module/settings/file-manager`
3. Grand access to perform commands etc at `admin/user/list`

To manage files go to `admin/tool/file-manager`