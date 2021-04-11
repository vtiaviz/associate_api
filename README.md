# Associates API

![](/screen.jpg)

This is just a simple CRUD API system with Table and Chart reloaded by jQuery. In the form are the fields: First Name, Last Name and Participation, the latter needs an insertion limit of up to 100%, so our API has a validation that blocks insertions above 100%. All fields are required and validate with codeigniter form_validation.

# Demo:
[Preview Demo](https://apivti.000webhostapp.com/)

# Instalation:
- Unzip the content in your favorite webserver folder.
- Create a data base called 'bd_api' in phpmysql
- Import the file 'bd_api.sql' to the data base 'bd_api'

# Use
- Start your webserver
- Access the API in your browser at the link 'https://localhost/api'
- Feel free to insert, update or delete something
- Take care with the limit of participations (100%)

# Functions:
- CRUD (Create, Read, Update and Delete)
- jQuery Refresh
- Chart
- Insert Limit

# Tecnologies

## Frontend

- Bootstrap 3
- Font Awesome
- jQuery
- Charts.js

## Backend

- PHP 7.1.33
- MySQL 5.7.31
- CodeIgniter 3
- Form_validation Codeigniter
- Transactions Codigniter
- CURL Codigniter

# Licence
MIT

