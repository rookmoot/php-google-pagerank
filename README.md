Google PageRank PHP Class
=========================


This class helps you to get an url Google PageRank. It has been written in PHP an is easy to use as you can see in the quick start exemple :

## Quick start

```php
<?php
require_once 'php-google-pagerank/PHPGooglePageRank.php';
$pgpr = new PHPGooglePageRank('http://www.google.com');
$pagerank = $pgpr->get();

```

## Authors

**Stéphane Bauland**

+ [http://www.facebook.com/rookmoot](http://www.facebook/rookmoot)
+ [http://github.com/rookmoot](http://github.com/rookmoot)


## Copyright and license

Copyright 2012 Diatelys SAS.

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this work except in compliance with the License.
You may obtain a copy of the License in the LICENSE file, or at:

  [http://www.apache.org/licenses/LICENSE-2.0](http://www.apache.org/licenses/LICENSE-2.0)

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
