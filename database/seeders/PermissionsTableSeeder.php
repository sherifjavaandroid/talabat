<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        /*
        LIST OF PERMISSIONS
view-banners
view-vendors
view-vendor-types
view-zones
view-categories
view-favourites
view-package-types
view-countries
view-states
view-cities
view-taxi
view-taxi-vehicle-types
view-taxi-vehicles
view-car-makes
view-car-models
view-taxi-payment-methods
view-taxi-pricing
view-taxi-settings
view-taxi-zones
view-fleets
manager-fleets
view-earning
my-earning
view-payout
view-subscription
view-report
view-coupon-report
view-referral-report
view-commission-report
view-vendor-report
view-customers-report
view-subscriptions-report
view-in-app-support
set-in-app-support
modify-vendor-document
change-order-cancel-reason
view-driver-documents

------------ CITYADMIN-\ADMIN-------
view-coupon-report
view-referral-report
view-commission-report
view-vendor-report
view-customers-report
view-subscriptions-report
view-tags
        */


        \DB::table('permissions')->delete();
        //
        $crudPermissions = []; //["user", "service", "banner", "category", "subcategory"];
        //fleet manager
        $fleetManagerPermissions = ["manage-fleet", "view-fleets"];
        //admin only permissions
        $adminRolePermissions = [
            "assign-permissions",
            "manager-fleets",
            "manage-subscriptions",
            "view-vendor-types",
            "view-zones",
            "view-banners",
            "view-cities",
            "view-states",
            "view-countries",
            "view-taxi",
            "view-taxi-vehicle-types",
            "view-taxi-vehicles",
            "view-car-makes",
            "view-car-models",
            "view-taxi-pricing",
            "view-taxi-settings",
            "view-taxi-payment-methods",
            "view-taxi-zones",
            "manage-payout",
            "view-reviews",
            "view-operations",
            "view-settings",
            "view-refund",
            "view-tags",
            "view-in-app-support",
            "view-summary-report",
            "view-flash-sales",
            "manage-extensions",
            "view-deleted-users",
            "mang-onboarding",
            "set-vendor-fees",
            "view-order-chat",
            "view-print-order",
            "view-loyalty",
            "view-loyalty-settings",
            "view-faq",
            "view-outstanding-payments",
            "new-taxi-order",
            "new-package-order",
            "view-wallet-transactions",
            "view-payment-accounts",
            //
            "view-driver-incentives",
            "view-drivers",
            "edit-driver",
            "delete-driver",
            "modify-vendor-document",
            "change-order-cancel-reason",
            "view-driver-documents",
            "view-vendor-documents",
            "auto-approve-product",
            "view-product-requests",
            "order-product-visibilities",
        ];
        //managers roles only
        $managerRolePermissions = ["manager-fleets", "my-subscription", 'my-earning'];
        //admin & manager permissions
        $adminManagerRolePermissions = [
            "view-report",
            "view-payout",
            "view-earning",
            "view-subscription",
            "view-payment-section",
            "view-payment-accounts",
        ];
        //admin & city-admin permissions
        $cityAdminRolePermissions = [
            "view-report",
            "view-subscription",
            "view-categories",
            "view-favourites",
            "view-package-types",
            "view-fleets",
            "view-product-report",
            "view-service-report",
            "view-coupon-report",
            "view-referral-report",
            "view-commission-report",
            "view-vendor-report",
            "view-customers-report",
            "view-subscriptions-report",
            "view-users",
            "view-delivery-addresses",
            "view-coupons",
            "view-vendor-earning",
            "vendor-earning-history",
            "driver-earning-history",
            "view-product-requests",
            "auto-approve-product",
        ];
        //permission for manager, city-admin & admin
        $allRolePermissions = [
            "view-earning",
            "manage-vendor",
            "view-report",
            "view-payout",
            "view-earning",
            "view-subscription",
            "view-vendors",
            "view-orders",
            "view-product-report",
        ];

        //creating the permissions
        $allPermissions = array_merge(
            $fleetManagerPermissions,
            $managerRolePermissions,
            $adminRolePermissions,
            $crudPermissions,
            $adminManagerRolePermissions,
            $allRolePermissions,
            $cityAdminRolePermissions
        );
        //
        foreach ($allPermissions as $mPermission) {
            Permission::firstorcreate(['name' => $mPermission]);
        }


        //for fleet manager
        $fleetManagerRole = Role::firstorcreate([
            'name' => 'fleet-manager'
        ], [
            'guard_name' => 'web'
        ]);
        $fleetManagerRole->syncPermissions($fleetManagerPermissions, $allRolePermissions);

        //admin role permissions
        $adminRole = Role::firstorcreate([
            'name' => 'admin'
        ], [
            'guard_name' => 'web'
        ]);
        $permissions = array_merge($adminRolePermissions, $adminManagerRolePermissions, $allRolePermissions, $cityAdminRolePermissions);
        $adminRole->syncPermissions($permissions);

        //manager role permissions
        $managerRole = Role::firstorcreate([
            'name' => 'manager'
        ], [
            'guard_name' => 'web'
        ]);
        $permissions = array_merge($managerRolePermissions, $adminManagerRolePermissions, $allRolePermissions);
        $managerRole->syncPermissions($permissions);


        //city-admin role permissions
        $cityAdminRole = Role::firstorcreate([
            'name' => 'city-admin'
        ], [
            'guard_name' => 'web'
        ]);
        $cityAdminRole->syncPermissions($cityAdminRolePermissions, $allRolePermissions);

        //others
    }
}
