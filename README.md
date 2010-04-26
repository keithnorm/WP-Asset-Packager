# Wordpress Asset Packager
This plugin includes a rails style helper method for including Javascript functions. BUT, the real power is in cacheing the files into one file, and using Google Closure to optimize the cache files.

    #php
    javascript_tag('jquery.cycle', 'jquery.quicksand', 'application', array('cache' => 'cache_file'));
    
    #html
    <script type="text/javascript" src="/path/to/theme/javascripts/cache_file.js"></script>
  
If you don't want to use a cache file just omit the final argument.

    #php
    javascript_tag('jquery.cycle', 'jquery.quicksand', 'application');
    
    #html
    <script type="text/javascript" src="/path/to/theme/javascripts/jquery.cycle.js"></script>
    <script type="text/javascript" src="/path/to/theme/javascripts/jquery.quicksand.js"></script>
    <script type="text/javascript" src="/path/to/theme/javascripts/application.js"></script>
    

## TODO
  * Add support for stylesheets
  * Add WP admin section for setting compilation levels and other settings

## License 

(The MIT License)

Copyright (c) 2010 Keith Norman <keithn@groupon.com>

Permission is hereby granted, free of charge, to any person obtaining
a copy of this software and associated documentation files (the
'Software'), to deal in the Software without restriction, including
without limitation the rights to use, copy, modify, merge, publish,
distribute, sublicense, and/or sell copies of the Software, and to
permit persons to whom the Software is furnished to do so, subject to
the following conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED 'AS IS', WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY
CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.