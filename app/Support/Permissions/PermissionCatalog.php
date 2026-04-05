<?php

namespace App\Support\Permissions;

final class PermissionCatalog
{
    /**
     * @return array<int, array{
     *     key: string,
     *     title: string,
     *     description: string,
     *     bulk_label: string,
     *     permissions: array<int, array{
     *         name: string,
     *         label: string,
     *         description: string
     *     }>
     * }>
     */
    public static function sections(): array
    {
        return [
            [
                'key' => 'users',
                'title' => 'Users',
                'description' => 'Kontrol akses CRUD untuk data user dan assignment akun.',
                'bulk_label' => 'Full CRUD',
                'permissions' => [
                    ['name' => 'view_users', 'label' => 'View', 'description' => 'Melihat daftar dan detail user.'],
                    ['name' => 'create_users', 'label' => 'Create', 'description' => 'Menambahkan user baru.'],
                    ['name' => 'update_users', 'label' => 'Update', 'description' => 'Mengubah data user yang ada.'],
                    ['name' => 'delete_users', 'label' => 'Delete', 'description' => 'Menghapus user dari sistem.'],
                ],
            ],
            [
                'key' => 'roles',
                'title' => 'Roles',
                'description' => 'Kontrol akses CRUD untuk role dan assignment module.',
                'bulk_label' => 'Full CRUD',
                'permissions' => [
                    ['name' => 'view_roles', 'label' => 'View', 'description' => 'Melihat daftar dan detail role.'],
                    ['name' => 'create_roles', 'label' => 'Create', 'description' => 'Membuat role baru.'],
                    ['name' => 'update_roles', 'label' => 'Update', 'description' => 'Memperbarui role yang ada.'],
                    ['name' => 'delete_roles', 'label' => 'Delete', 'description' => 'Menghapus role non-sistem.'],
                ],
            ],
            [
                'key' => 'changelogs',
                'title' => 'Changelogs',
                'description' => 'Kontrol akses CRUD untuk catatan rilis dan update aplikasi.',
                'bulk_label' => 'Full CRUD',
                'permissions' => [
                    ['name' => 'view_changelogs', 'label' => 'View', 'description' => 'Melihat daftar changelog dan catatan rilis.'],
                    ['name' => 'create_changelogs', 'label' => 'Create', 'description' => 'Membuat changelog baru.'],
                    ['name' => 'update_changelogs', 'label' => 'Update', 'description' => 'Memperbarui changelog yang ada.'],
                    ['name' => 'delete_changelogs', 'label' => 'Delete', 'description' => 'Menghapus changelog dari sistem.'],
                ],
            ],
            [
                'key' => 'work_progress',
                'title' => 'Work Progress',
                'description' => 'Kontrol akses CRUD untuk pelacakan progress pekerjaan dan tindak lanjut tim.',
                'bulk_label' => 'Full CRUD',
                'permissions' => [
                    ['name' => 'view_work_progress', 'label' => 'View', 'description' => 'Melihat daftar progress kerja.'],
                    ['name' => 'create_work_progress', 'label' => 'Create', 'description' => 'Menambahkan progress kerja baru.'],
                    ['name' => 'update_work_progress', 'label' => 'Update', 'description' => 'Memperbarui status dan catatan progress kerja.'],
                    ['name' => 'delete_work_progress', 'label' => 'Delete', 'description' => 'Menghapus progress kerja dari sistem.'],
                ],
            ],
            [
                'key' => 'institutions',
                'title' => 'Institution Settings',
                'description' => 'Kontrol akses untuk pengaturan branding dan identitas institusi.',
                'bulk_label' => 'Full Access',
                'permissions' => [
                    ['name' => 'view_institutions', 'label' => 'View', 'description' => 'Melihat halaman pengaturan institusi.'],
                    ['name' => 'update_institutions', 'label' => 'Update', 'description' => 'Mengubah data institusi, logo, dan background login.'],
                ],
            ],
            [
                'key' => 'permissions',
                'title' => 'Permission Settings',
                'description' => 'Kontrol akses CRUD untuk permission yang dipakai di aplikasi.',
                'bulk_label' => 'Full CRUD',
                'permissions' => [
                    ['name' => 'view_permissions', 'label' => 'View', 'description' => 'Melihat daftar permission.'],
                    ['name' => 'create_permissions', 'label' => 'Create', 'description' => 'Membuat permission baru.'],
                    ['name' => 'update_permissions', 'label' => 'Update', 'description' => 'Mengubah permission custom.'],
                    ['name' => 'delete_permissions', 'label' => 'Delete', 'description' => 'Menghapus permission custom.'],
                ],
            ],
            [
                'key' => 'activity_logs',
                'title' => 'Activity Log',
                'description' => 'Kontrol akses untuk melihat laporan aktivitas user dan sistem secara otomatis.',
                'bulk_label' => 'View Access',
                'permissions' => [
                    ['name' => 'view_activity_logs', 'label' => 'View', 'description' => 'Melihat daftar activity log.'],
                ],
            ],
            [
                'key' => 'error_logs',
                'title' => 'Error Logs',
                'description' => 'Kontrol akses untuk melihat laporan error aplikasi yang tercatat otomatis.',
                'bulk_label' => 'View Access',
                'permissions' => [
                    ['name' => 'view_error_logs', 'label' => 'View', 'description' => 'Melihat daftar error log.'],
                ],
            ],
        ];
    }

    /**
     * @return list<string>
     */
    public static function names(): array
    {
        return collect(self::sections())
            ->flatMap(fn (array $section) => $section['permissions'])
            ->pluck('name')
            ->values()
            ->all();
    }

    /**
     * @return array<string, array{
     *     section_key: string,
     *     section_title: string,
     *     section_description: string,
     *     name: string,
     *     label: string,
     *     description: string
     * }>
     */
    public static function metadata(): array
    {
        return collect(self::sections())
            ->flatMap(function (array $section) {
                return collect($section['permissions'])->mapWithKeys(function (array $permission) use ($section) {
                    return [
                        $permission['name'] => [
                            'section_key' => $section['key'],
                            'section_title' => $section['title'],
                            'section_description' => $section['description'],
                            'name' => $permission['name'],
                            'label' => $permission['label'],
                            'description' => $permission['description'],
                        ],
                    ];
                });
            })
            ->all();
    }
}
