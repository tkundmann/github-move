
# Run "npm install" (will rebuild "node_modules" folder)
npm install

#Run "gulp less" (will rebuild *.less files from resources/assets and put them into public/css)
gulp less

# Run "artisan config:clear" - clear config caches to be sure
/usr/bin/env php artisan config:clear

#Run "artisan view:clear" - clear view caches to be sure
/usr/bin/env php

#Run "artisan cache:clear" - clear all the other caches to be sure
/usr/bin/env php artisan view:clear

#Run "artisan clear-compiled" - clear artisan compiled classes
/usr/bin/env php artisan clear-compiled

#Run "artisan optimize" - optimize framework
/usr/bin/env php artisan optimize