**Master HEAD:** _Master branch HEAD commit hash at time of post_

**Laravel Version:** _Laravel version of the app you are using Quark with_

**Steps to Reproduce:** _if any_

**Issue Presentation Code:**
```php
    $limit = $request->has('limit') ? $request->limit : 50; // The line contatining the bug.
```