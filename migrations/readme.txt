This folder to store generated migration classes using command line:

php vendor/bin/doctrine orm:clear-cache:metadata
php vendor/bin/doctrine orm:schema-tool:drop --force
php vendor/bin/doctrine orm:schema-tool:create


php migrations.php generate
php migrations.php migrate


    public function up(Schema $schema)
    {
        $tenantId = "1";

        //Tenants
        $paramTenants = ['1', 'haithanh', 'haithanh', ''];

        // Resources
        $paramResources =
            [
                ['1', $tenantId, 'infohotel', '', '', ''],
                ['2', $tenantId, 'guest', '', '', ''],
                ['3', $tenantId, 'room', '', '', ''],
                ['4', $tenantId, 'service', '', '', ''],
                ['5', $tenantId, 'employee', '', '', ''],
                ['6', $tenantId, 'device', '', '', ''],
                ['7', $tenantId, 'pricequote', '', '', ''],
                ['8', $tenantId, 'bookroom', '', '', ''],
                ['9', $tenantId, 'checkin', '', '', ''],
                ['10', $tenantId, 'checkout', '', '', ''],
                ['11', $tenantId, 'report', '', '', ''],
                ['12', $tenantId, 'priceconfig', '', '', ''],
                ['13', $tenantId, 'user', '', '', ''],
                ['14', $tenantId, 'customer', '', '', ''],
            ];

        // User Roles
        $paramUserRoles =
            [
                ['1', $tenantId, 'Admin', '', ''],
                ['2', $tenantId, 'Receptionist', '', ''],
            ];

        // User 
        $paramUsers =
            [
                ['1', $tenantId, '1', 'Admin', 'admin', 'admin', '', '', '', ''],
                ['2', $tenantId, '2', 'Receptionist', 'receptionist', 'Receptionist', '', '', '', ''],
            ];

        // Privileges
        $paramPrivileges =
            [
                ['1', $tenantId, 'Hien Thi thong tin khach san', 'read', '', '', '1'],

                ['2', $tenantId, 'Tao moi mot khach hang', 'create', '', '', '2'],
                ['3', $tenantId, 'Hien thi danh sach khach hang', 'read', '', '', '2'],
                ['4', $tenantId, 'Cap nhat thong tin khach hang', 'update', '', '', '2'],
                ['5', $tenantId, 'Xoa mot khach hang', 'delete', '', '', '2'],

                ['6', $tenantId, 'Tao moi mot phong', 'create', '', '', '3'],
                ['7', $tenantId, 'Hien thi danh sach phong', 'read', '', '', '3'],
                ['8', $tenantId, 'Cap nhat thong tin phong', 'update', '', '', '3'],
                ['9', $tenantId, 'Xoa mot phong', 'delete', '', '', '3'],

                ['10', $tenantId, 'Tao moi mot dich vu', 'create', '', '', '4'],
                ['11', $tenantId, 'Hien thi danh sach dich vu', 'read', '', '', '4'],
                ['12', $tenantId, 'Cap nhat thong tin dich vu', 'update', '', '', '4'],
                ['13', $tenantId, 'Xoa mot dich vu', 'delete', '', '', '4'],

                ['14', $tenantId, 'Tao moi mot nhan vien', 'create', '', '', '5'],
                ['15', $tenantId, 'Hien thi danh sach nhan vien', 'read', '', '', '5'],
                ['16', $tenantId, 'Cap nhat thong tin nhan vien', 'update', '', '', '5'],
                ['17', $tenantId, 'Xoa mot nhan vien', 'delete', '', '', '5'],

                ['18', $tenantId, 'Tao moi mot thiet bi', 'create', '', '', '6'],
                ['19', $tenantId, 'Hien thi danh sach thiet bi', 'read', '', '', '6'],
                ['20', $tenantId, 'Cap nhat thong tin thiet bi', 'update', '', '', '6'],
                ['21', $tenantId, 'Xoa mot thiet bi', 'delete', '', '', '6'],

                ['22', $tenantId, 'Hien thi trang bao gia phong', 'read', '', '', '7'],
                ['23', $tenantId, 'Hien thi trang dat phong', 'read', '', '', '8'],
                ['24', $tenantId, 'Hien thi trang nhan phong', 'read', '', '', '9'],
                ['25', $tenantId, 'Hien thi trang tra phong', 'read', '', '', '10'],
                ['26', $tenantId, 'Hien thi trang bao cao', 'read', '', '', '11'],

                ['27', $tenantId, 'Hien thi trang cau hinh gia phong', 'read', '', '', '12'],
                ['28', $tenantId, 'Cap nhat thong tin gia phong', 'update', '', '', '12'],

                ['29', $tenantId, 'Tao moi mot user', 'create', '', '', '13'],
                ['30', $tenantId, 'Hien thi danh sach user', 'read', '', '', '13'],
                ['31', $tenantId, 'Cap nhat thong tin user', 'update', '', '', '13'],
                ['32', $tenantId, 'Xoa mot user', 'delete', '', '', '13'],

                ['33', $tenantId, 'Tao moi mot customer', 'create', '', '', '14'],
                ['34', $tenantId, 'Hien thi danh sach customer', 'read', '', '', '14'],
                ['35', $tenantId, 'Cap nhat thong tin customer', 'update', '', '', '14'],
                ['36', $tenantId, 'Xoa mot customer', 'delete', '', '', '14'],
            ]; // 52

        // User Role Privileges
        $paramUserRolePrivileges =
            [
                ['1', $tenantId, '1', '1', '', ''],
                ['2', $tenantId, '1', '2', '', ''],
                ['3', $tenantId, '1', '3', '', ''],
                ['4', $tenantId, '1', '4', '', ''],
                ['5', $tenantId, '1', '5', '', ''],
                ['6', $tenantId, '1', '6', '', ''],
                ['7', $tenantId, '1', '7', '', ''],
                ['8', $tenantId, '1', '8', '', ''],
                ['9', $tenantId, '1', '9', '', ''],
                ['10', $tenantId, '1', '10', '', ''],
                ['11', $tenantId, '1', '11', '', ''],
                ['12', $tenantId, '1', '12', '', ''],
                ['13', $tenantId, '1', '13', '', ''],
                ['14', $tenantId, '1', '14', '', ''],
                ['15', $tenantId, '1', '15', '', ''],
                ['16', $tenantId, '1', '16', '', ''],
                ['17', $tenantId, '1', '17', '', ''],
                ['18', $tenantId, '1', '18', '', ''],
                ['19', $tenantId, '1', '19', '', ''],
                ['20', $tenantId, '1', '20', '', ''],
                ['21', $tenantId, '1', '21', '', ''],
                ['22', $tenantId, '1', '22', '', ''],
                ['23', $tenantId, '1', '23', '', ''],
                ['24', $tenantId, '1', '24', '', ''],
                ['25', $tenantId, '1', '25', '', ''],
                ['26', $tenantId, '1', '26', '', ''],
                ['27', $tenantId, '1', '27', '', ''],
                ['28', $tenantId, '1', '28', '', ''],
                ['29', $tenantId, '1', '29', '', ''],
                ['30', $tenantId, '1', '30', '', ''],
                ['31', $tenantId, '1', '31', '', ''],
                ['32', $tenantId, '1', '32', '', ''],
                ['33', $tenantId, '1', '33', '', ''],
                ['34', $tenantId, '1', '34', '', ''],
                ['35', $tenantId, '1', '35', '', ''],
                ['36', $tenantId, '1', '36', '', ''],

                ['37', $tenantId, '2', '1', '', ''],
                ['38', $tenantId, '2', '2', '', ''],
                ['39', $tenantId, '2', '3', '', ''],
                ['40', $tenantId, '2', '4', '', ''],
                ['41', $tenantId, '2', '7', '', ''],
                ['42', $tenantId, '2', '11', '', ''],
                ['43', $tenantId, '2', '15', '', ''],
                ['44', $tenantId, '2', '19', '', ''],
                ['45', $tenantId, '2', '22', '', ''],
                ['46', $tenantId, '2', '23', '', ''],
                ['47', $tenantId, '2', '24', '', ''],
                ['48', $tenantId, '2', '25', '', ''],
                ['49', $tenantId, '2', '26', '', ''],
                ['50', $tenantId, '2', '27', '', ''],


            ];

        // Floor 
        $paramFloors =
            [
                ['1', $tenantId, 'Tầng 01', 'floor01', '1', '', '', '', ''],
                ['2', $tenantId, 'Tầng 02', 'floor02', '1', '', '', '', ''],
                ['3', $tenantId, 'Tầng 03', 'floor03', '1', '', '', '', ''],
                ['4', $tenantId, 'Tầng 04', 'floor04', '1', '', '', '', ''],
            ];

        // RoomType 
        $paramRoomTypes =
            [
                ['1', $tenantId, 'Phòng đơn', '60000', 'single', '1', '2', '', '', '', ''],
                ['2', $tenantId, 'Phòng đôi', '10000', 'double', '1', '4', '', '', '', ''],
            ];

        // Room 
        $paramRooms =
            [
                ['1', $tenantId, '1', '1', 'Phòng 101', '1', '', '', '', '', ''],
                ['2', $tenantId, '1', '1', 'Phòng 102', '1', '', '', '', '', ''],
                ['3', $tenantId, '1', '2', 'Phòng 103', '1', '', '', '', '', ''],
                ['4', $tenantId, '1', '2', 'Phòng 104', '1', '', '', '', '', ''],

                ['5', $tenantId, '2', '1', 'Phòng 201', '1', '', '', '', '', ''],
                ['6', $tenantId, '2', '1', 'Phòng 202', '1', '', '', '', '', ''],
                ['7', $tenantId, '2', '2', 'Phòng 203', '1', '', '', '', '', ''],
                ['8', $tenantId, '2', '2', 'Phòng 204', '1', '', '', '', '', ''],

                ['9', $tenantId, '3', '1', 'Phòng 301', '1', '', '', '', '', ''],
                ['10', $tenantId, '3', '1', 'Phòng 302', '1', '', '', '', '', ''],
                ['11', $tenantId, '3', '2', 'Phòng 303', '1', '', '', '', '', ''],
                ['12', $tenantId, '3', '2', 'Phòng 304', '1', '', '', '', '', ''],

                ['13', $tenantId, '4', '1', 'Phòng 401', '1', '', '', '', '', ''],
                ['14', $tenantId, '4', '1', 'Phòng 402', '1', '', '', '', '', ''],
                ['15', $tenantId, '4', '2', 'Phòng 403', '1', '', '', '', '', ''],
                ['16', $tenantId, '4', '2', 'Phòng 404', '1', '', '', '', '', ''],
            ];


        // Guests
        $paramGuests =
            [
                ['1', $tenantId, 'Nguyễn Văn Hội', 'Nam', '098772383', '215465936', '2030-10-03 00:00:00', '2015-10-03 00:00:00', 'Bình Định', '1998', 'Thủ đức, Hồ Chí Minh', '', '', '', ''],
                ['2', $tenantId, 'Lê Hoài Đức', 'Nam', '0389457021', '215465932', '2030-10-03 00:00:00', '2015-10-03 00:00:00', 'Buôn Mê Thuột', '1992', 'Gò Vấp, Hồ Chí Minh', '', '', '', ''],
                ['3', $tenantId, 'Nguyễn Thị Trúc Quỳnh', 'Nữ', '09821230321', '215465946', '2030-10-03 00:00:00', '2015-10-03 00:00:00', 'Phú Yên', '1989', 'Quận 2, Thảo Điền, Hồ Chí Minh', '', '', '', ''],
                ['4', $tenantId, 'Đặng Thái Bảo', 'Nam', '0398232313', '215465964', '2030-10-03 00:00:00', '2015-10-03 00:00:00', 'Hà Nội', '2002', 'Bình Dương, Hồ Chí Minh', '', '', '', ''],
                ['5', $tenantId, 'Hoàng Lê Bách', 'Nam', '0982314121', '215465236', '2030-10-03 00:00:00', '2015-10-03 00:00:00', 'Hồ Chí Minh', '1996', 'Tây Ninh, Hồ Chí Minh', '', '', '', '']
            ];


        // Service Types
        $paramServiceTypes =
            [
                ['1', $tenantId, 'Đồ ăn nhanh', 'fastfood', '1'],
                ['2', $tenantId, 'Nước ngọt', 'drinksoft', '1'],
                ['3', $tenantId, 'Sinh tố', 'fruit', '1'],
                ['4', $tenantId, 'Bia', 'beer', '1'],
                ['5', $tenantId, 'Thuốc Lá', 'cigarette', '1'],
            ];


        // Services
        $paramServices =
            [
                ['1', $tenantId, '1', 'Bánh ngọt', '12000', 'Bịch', '1', 'fastfood1'],
                ['2', $tenantId, '1', 'Bánh bông lan', '25000', 'Bịch', '1', 'fastfood2'],
                ['3', $tenantId, '1', 'Bánh xoài', '18000', 'Bịch', '1', 'fastfood3'],
                ['4', $tenantId, '1', 'Bánh Nutifood', '20000', 'Bịch', '1', 'fastfood4'],
                ['5', $tenantId, '1', 'Cơm Chiên', '30000', 'Dĩa', '1', 'fastfood5'],
                ['6', $tenantId, '1', 'Mì Xào trứng', '25000', 'Dĩa', '1', 'fastfood6'],

                ['7', $tenantId, '2', 'Sting', '15000', 'Chai', '1', 'drinksoft1'],
                ['8', $tenantId, '2', 'Pepsi', '15000', 'Chai', '1', 'drinksoft2'],
                ['9', $tenantId, '2', 'Coca Cola', '15000', 'Chai', '1', 'drinksoft3'],
                ['10', $tenantId, '2', 'Nutifood', '15000', 'Chai', '1', 'drinksoft4'],
                ['11', $tenantId, '2', 'Revice', '15000', 'Chai', '1', 'drinksoft5'],
                ['12', $tenantId, '2', 'NumberOne', '15000', 'Chai', '1', 'drinksoft6'],

                ['13', $tenantId, '3', 'Sinh tố bơ', '20000', 'Ly', '1', 'fruit1'],
                ['14', $tenantId, '3', 'Sinh tố dừa', '20000', 'Ly', '1', 'fruit2'],
                ['15', $tenantId, '3', 'Sinh tố mãng cầu', '20000', 'Ly', '1', 'fruit3'],
                ['16', $tenantId, '3', 'Sinh tố xoài', '20000', 'Ly', '1', 'fruit4'],
                ['17', $tenantId, '3', 'Sinh tố dưa hấu', '20000', 'Ly', '1', 'fruit5'],
                ['18', $tenantId, '3', 'Sinh tố thập cẩm', '20000', 'Ly', '1', 'fruit6'],

                ['19', $tenantId, '4', 'Bia Tiger', '25000', 'Lon', '1', 'beer1'],
                ['20', $tenantId, '4', 'Bia Heniken', '25000', 'Lon', '1', 'beer2'],
                ['21', $tenantId, '4', 'Bia Budweiser', '25000', 'Lon', '1', 'beer3'],
                ['22', $tenantId, '4', 'Bia Sài gon xanh', '25000', 'Lon', '1', 'beer4'],
                ['23', $tenantId, '4', 'Bia LeO', '25000', 'Lon', '1', 'beer5'],
                ['24', $tenantId, '4', 'Bia 333', '25000', 'Lon', '1', 'beer6'],

                ['25', $tenantId, '5', 'Thuốc ngựa trắng', '25000', 'Gói', '1', 'cigarette1'],
                ['26', $tenantId, '5', 'Thuốc Zet', '25000', 'Gói', '1', 'cigarette2'],
                ['27', $tenantId, '5', 'Thuốc 3 số việt', '50000', 'Gói', '1', 'cigarette3'],
                ['28', $tenantId, '5', 'Thuốc 3 số Anh', '70000', 'Gói', '1', 'cigarette4'],
                ['29', $tenantId, '5', 'Thuốc xì gà', '120000', 'Gói', '1', 'cigarette5'],
                ['30', $tenantId, '5', 'Thuốc Ero', '15000', 'Gói', '1', 'cigarette6'],


            ];

        // Price Rent Day
        $paramPrices =
            [
                ['1', $tenantId, '1', 'DAY', 'DAY_DEFAULT', '400000', '', ''],
                ['2', $tenantId, '2', 'DAY', 'DAY_DEFAULT', '800000', '', ''],
                ['3', $tenantId, '1', 'DAY', 'DAY_WEEKEND', '500000', '', ''],
                ['4', $tenantId, '2', 'DAY', 'DAY_WEEKEND', '1000000', '', ''],
                ['5', $tenantId, '1', 'DAY', 'FEE_EARLY', '50000', '', ''],
                ['6', $tenantId, '2', 'DAY', 'FEE_EARLY', '100000', '', ''],
                ['7', $tenantId, '1', 'DAY', 'FEE_OT', '40000', '', ''],
                ['8', $tenantId, '2', 'DAY', 'FEE_OT', '80000', '', ''],
            ];

        // Price  Rent Day, Hour, Night for hai thanh 2 hotel
        $paramConfigPrice =
            [
                ['1', $tenantId, 'firstHourPrice1', '60000'],
                ['2', $tenantId, 'nextHourPrice1', '20000'],
                ['3', $tenantId, 'firstHourAfter23h1', '100000'],
                ['4', $tenantId, 'nextHourAfter23h1', '30000'],
                ['5', $tenantId, 'dayPrice1', '330000'],
                ['6', $tenantId, 'nightPrice1', '200000'],
                ['7', $tenantId, 'weekendDayPrice1', '400000'],
                ['8', $tenantId, 'weekendNightPrice1', '250000'],
                ['9', $tenantId, 'OTHourPrice1', '30000'],
                ['10', $tenantId, 'earlyHourPrice1', '20000'],
                ['11', $tenantId, 'maxPeople1', '2'],
                ['12', $tenantId, 'surcharge1', '50000'],
                ['13', $tenantId, 'firstHourPrice2', '100000'],
                ['14', $tenantId, 'nextHourPrice2', '30000'],
                ['15', $tenantId, 'firstHourAfter23h2', '150000'],
                ['16', $tenantId, 'nextHourAfter23h2', '50000'],
                ['17', $tenantId, 'dayPrice2', '400000'],
                ['18', $tenantId, 'nightPrice2', '300000'],
                ['19', $tenantId, 'weekendDayPrice2', '500000'],
                ['20', $tenantId, 'weekendNightPrice2', '400000'],
                ['21', $tenantId, 'OTHourPrice2', '50000'],
                ['22', $tenantId, 'earlyHourPrice2', '40000'],
                ['23', $tenantId, 'maxPeople2', '4'],
                ['24', $tenantId, 'surcharge2', '50000'],
            ];




        $sqlTenants = "INSERT INTO tenants (id, name, code, setting) VALUES (?, ?, ?,?)";
        $sqlUserRoles = "INSERT INTO user_role (id, tenant_id, name,created_at, updated_at) VALUES (?, ?, ?, ?,?)";
        $sqlUsers = "INSERT INTO user (id, tenant_id, user_role_id ,username, password, user_type,created_by, created_at, updated_by, updated_at) VALUES (?, ?, ?, ?,?,?,?,?,?,?)";
        $sqlResource = "INSERT INTO resource (id, tenant_id, name,created_at, updated_at, description) VALUES (?, ?, ?, ?,?,?)";
        $sqlPrivileges = "INSERT INTO privileges (id, tenant_id, name,action ,created_at, updated_at, resource_id) VALUES (?, ?, ?, ?,?,?,?)";
        $sqlUserRolePrivileges = "INSERT INTO user_role_privileges (id, tenant_id, user_role_id , privileges_id ,created_at, updated_at) VALUES (?, ?, ?, ?,?,?)";

        $sqlFloors = "INSERT INTO floor (id, tenant_id, name, code , active, created_by, created_at, updated_by, updated_at) VALUES (?,?,?,?,?,?,?,?,?)";
        $sqlRoomTypes = "INSERT INTO room_type (id, tenant_id, name ,price ,code , active , people_max , created_by, created_at, updated_by, updated_at) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
        $sqlRooms = "INSERT INTO room (id, tenant_id, floor_id,roomtype_id ,name, status ,description, created_by, created_at, updated_by, updated_at) VALUES (?,?,?,?,?,?,?,?,?,?,?)";

        $sqlGuests = "INSERT INTO guest (id, tenant_id, name ,gender ,phone_number, id_card ,id_card_expiry, id_card_isssue , id_card_place, year_birth, address,  created_by, created_at, updated_by, updated_at) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

        $sqlServiceTypes = "INSERT INTO service_types (id, tenant_id,  name, code, active ) VALUES (?,?,?,?,?)";
        $sqlServices = "INSERT INTO service (id, tenant_id, servicetype_id, name ,price , unit, status, code ) VALUES (?,?,?,?,?,?,?,?)";

        $sqlPrices = "INSERT INTO price (id, tenant_id, roomtype_id, rent_type , charge_type , price, start_time, end_time) VALUES (?,?,?,?,?,?,?,?)";

        $sqlConfigPrices = "INSERT INTO config (id, tenant_id, `key`, `value` ) VALUES (?,?,?,?)";

        $this->addSql($sqlTenants, $paramTenants);
        foreach ($paramConfigPrice as $param) {
            $this->addSql($sqlConfigPrices, $param);
        }

        foreach ($paramUserRoles as $param) {
            $this->addSql($sqlUserRoles, $param);
        }

        foreach ($paramUsers as $param) {
            $this->addSql($sqlUsers, $param);
        }


        foreach ($paramResources as $param) {
            $this->addSql($sqlResource, $param);
        }

        foreach ($paramPrivileges as $param) {
            $this->addSql($sqlPrivileges, $param);
        }

        foreach ($paramUserRolePrivileges as $param) {
            $this->addSql($sqlUserRolePrivileges, $param);
        }

        foreach ($paramFloors as $param) {
            $this->addSql($sqlFloors, $param);
        }

        foreach ($paramRoomTypes as $param) {
            $this->addSql($sqlRoomTypes, $param);
        }

        foreach ($paramRooms as $param) {
            $this->addSql($sqlRooms, $param);
        }

        foreach ($paramGuests as $param) {
            $this->addSql($sqlGuests, $param);
        }

        foreach ($paramServiceTypes as $param) {
            $this->addSql($sqlServiceTypes, $param);
        }

        foreach ($paramServices as $param) {
            $this->addSql($sqlServices, $param);
        }

        foreach ($paramPrices as $param) {
            $this->addSql($sqlPrices, $param);
        }

      
    }



