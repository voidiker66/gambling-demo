# gambling-demo


Installation steps:

1. `git clone https://github.com/voidiker66/gambling-demo.git`
2. `cd gambling-demo`
3. `composer i`
5. `php artisan migrate`
6. `npm i`
7. `npm run build`
8. Start the MySQL server (I used XAMPP)
9. `php artisan serve`
10. Go to http://localhost:8000/


# Features

1. Use of Laravel, HTML/TailwindCSS, Vue, and MySQL
2. User authentication with restricted API calls
3. File import for either JSON or CSV list of associates
4. PHPUnit unit tests for both JSON and CSV file handlers
5. Table display of imported associates
6. Table can be sorted by clicking column headers
7. Table can be filtered by name, affiliate id, and a calculated distance based on the range, latitude, and longitude fields
8. Table can be paginated by selecting the Results Per Page dropdown
