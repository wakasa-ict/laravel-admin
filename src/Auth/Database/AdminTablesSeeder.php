<?php

namespace Encore\Admin\Auth\Database;

use Illuminate\Database\Seeder;

class AdminTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create a user.
        Administrator::truncate();
        Administrator::create([
            'username' => 'systemadmin',
            'password' => bcrypt('systemadmin'),
            'name'     => 'システム管理者',
        ]);

        // create a role.
        Role::truncate();
        Role::create([
            'name' => 'Systemadmin',
            'slug' => 'systemadmin',
        ]);

        // add role to user.
        Administrator::where('username', 'systemadmin')->first()->roles()->save(Role::where('slug', 'systemadmin')->first());

        // create a user.
        Administrator::create([
            'username' => 'admin',
            'password' => bcrypt('admin'),
            'name'     => '特権管理者',
        ]);

        // create a role.
        Role::create([
            'name' => 'Administrator',
            'slug' => 'administrator',
        ]);

        // add role to user.
        Administrator::where('username', 'admin')->first()->roles()->save(Role::where('slug', 'administrator')->first());

        // create a webadmin user.
        Administrator::create([
            'username' => 'webadmin',
            'password' => bcrypt('webadmin'),
            'name'     => 'Web管理者',
        ]);

        // create a webadmin role.
        Role::create([
            'name' => 'Webadmin',
            'slug' => 'webadmin',
        ]);

        // add role to user.
        Administrator::where('username', 'webadmin')->first()->roles()->save(Role::where('slug','webadmin')->first());

        //create a permission
        Permission::truncate();
        Permission::insert([
            [
                'name'        => 'All permission',
                'slug'        => '*',
                'http_method' => '',
                'http_path'   => '*',
            ],
            [
                'name'        => 'Dashboard',
                'slug'        => 'dashboard',
                'http_method' => 'GET',
                'http_path'   => '/',
            ],
            [
                'name'        => 'Login',
                'slug'        => 'auth.login',
                'http_method' => '',
                'http_path'   => "/auth/login\r\n/auth/logout",
            ],
            [
                'name'        => 'User setting',
                'slug'        => 'auth.setting',
                'http_method' => 'GET,PUT',
                'http_path'   => '/auth/setting',
            ],
            [
                'name'        => 'Auth management',
                'slug'        => 'auth.management',
                'http_method' => '',
                'http_path'   => "/auth/roles\r\n/auth/permissions\r\n/auth/menu\r\n/auth/logs",
            ],
            [
                'name'        => 'Administrator',
                'slug'        => 'administrator',
                'http_method' => '',
                'http_path'   => "/logs\r\n/administrators\r\nadministrators/*",
            ],
            [
                'name'        => 'Contents',
                'slug'        => 'contents',
                'http_method' => '',
                'http_path'   => "/examples\r\n/examples/*",
            ],
            [
                'name'        => 'Sortable',
                'slug'        => 'sortable',
                'http_method' => '',
                'http_path'   => "/_grid-sortable_*",
            ],
        ]);

        // systemadmin
        Role::first()->permissions()->save(Permission::first());

        // administrator
        Role::where('slug','administrator')->first()->permissions()->save(Permission::where('slug','dashboard')->first());
        Role::where('slug','administrator')->first()->permissions()->save(Permission::where('slug','auth.login')->first());
        Role::where('slug','administrator')->first()->permissions()->save(Permission::where('slug','administrator')->first());
        Role::where('slug','administrator')->first()->permissions()->save(Permission::where('slug','contents')->first());
        Role::where('slug','administrator')->first()->permissions()->save(Permission::where('slug','sortable')->first());

        // webadmin
        Role::where('slug','webadmin')->first()->permissions()->save(Permission::where('slug','dashboard')->first());
        Role::where('slug','webadmin')->first()->permissions()->save(Permission::where('slug','auth.login')->first());
        Role::where('slug','webadmin')->first()->permissions()->save(Permission::where('slug','contents')->first());
        Role::where('slug','webadmin')->first()->permissions()->save(Permission::where('slug','sortable')->first());

        // add default menus.
        Menu::truncate();
        Menu::insert([
            [
                'parent_id' => 0,
                'order'     => 1,
                'title'     => 'HOME',
                'icon'      => 'fa-home',
                'uri'       => '/',
            ],
            [
                'parent_id' => 0,
                'order'     => 2,
                'title'     => '管理者',
                'icon'      => 'fa-user',
                'uri'       => 'administrators',
            ],
            [
                'parent_id' => 0,
                'order'     => 3,
                'title'     => '操作ログ',
                'icon'      => 'fa-history',
                'uri'       => 'logs',
            ],
            [
                'parent_id' => 0,
                'order'     => 4,
                'title'     => 'Admin',
                'icon'      => 'fa-tasks',
                'uri'       => '',
            ],
            [
                'parent_id' => 4,
                'order'     => 5,
                'title'     => 'Users',
                'icon'      => 'fa-users',
                'uri'       => 'auth/users',
            ],
            [
                'parent_id' => 4,
                'order'     => 6,
                'title'     => 'Roles',
                'icon'      => 'fa-user',
                'uri'       => 'auth/roles',
            ],
            [
                'parent_id' => 4,
                'order'     => 7,
                'title'     => 'Permission',
                'icon'      => 'fa-ban',
                'uri'       => 'auth/permissions',
            ],
            [
                'parent_id' => 4,
                'order'     => 8,
                'title'     => 'Menu',
                'icon'      => 'fa-bars',
                'uri'       => 'auth/menu',
            ],
            [
                'parent_id' => 4,
                'order'     => 9,
                'title'     => 'Operation log',
                'icon'      => 'fa-history',
                'uri'       => 'auth/logs',
            ],
        ]);

        // add role to menu.
        Menu::where('uri', 'administrators')->first()->roles()->save(Role::where('slug', 'systemadmin')->first());
        Menu::where('uri', 'administrators')->first()->roles()->save(Role::where('slug', 'administrator')->first());
        Menu::where('uri', 'logs')->first()->roles()->save(Role::where('slug', 'systemadmin')->first());
        Menu::where('uri', 'logs')->first()->roles()->save(Role::where('slug', 'administrator')->first());
        Menu::where('title', 'admin')->first()->roles()->save(Role::where('slug', 'systemadmin')->first());
    }
}
