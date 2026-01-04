## Reseller User Management Module

Setup Instructions:
1) Clone git repository.
2) Repos contain vendor folder. So no need to run composer install.
3) If you want, you can copy .env.example to .env
4) If not want, wee include worked example .env file in repo.
5) Run php artisan key:generate.
6) Database is ready to use sqlite database.sqlite file located in the database folder.

## How to access Filament panels
After You run: php artisan serve, or start application in your browser, the
project runs immediately, and You can access Filament panels.
You can access Filament panels using the following credentials:

Credentials for admin:
email: admin@admin.com,
password: admin , (of course bcrypt in the database)

Credentials for normal user (agent)
email: agent@agent.com,
password: agent,

If You want clear database, you need run the next command:
php artisan migrate:fresh --seed
You can access Filament panels using the same, 
above mentioned credentials.

If You want another email, password credentials,
you can change it in seeder file, located in folder
database/seeders/DatabaseSeeder.php

## Technology Overview

used technologies:
- Laravel 12
- Filament 4
- Livewire 3
- SQLite database

## Developer Guide

Multitenansy is implemented according to the following guide:
https://filamentphp.com/docs/4.x/users/tenancy#setting-up-tenancy

By default, in database.sqlite created two teams: admin and reseller.
Also by default created two users: Admin and Agent. 
In each team, we can create users with two roles:
 - reseller_admin can full crud, delete, bulk deletion, change password.   
 - reseller_agent only can view all users and create new user.
Users from one team can't see users data from another team.

If you fresh database, with seeder (php artisan migrate:fresh --seed) 
You can access Filament panel using the credentials from part 
"How to access Filament panels". After your enter credentails 
for empty database, first time, its need enter data for team: 
name and slug. Its need one time.

For add more resources, you can use "New Reseller User" button.
