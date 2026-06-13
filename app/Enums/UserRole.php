<?php

namespace App\Enums;

enum UserRole: string
{
    case OWNER = 'owner';
    case ADMIN = 'admin';
    case MANAGER = 'manager';
    case CASHIER = 'cashier';
    case MARKETER = 'marketer';
    case INVENTORY_CLERK = 'inventory_clerk';
    case CUSTOMER_SUPPORT = 'customer_support';
    case FINANCE_STAFF = 'finance_staff';
    case VIEWER = 'viewer';
}
