INSERT INTO roles(`name`, `permissions`) VALUES('Admin', '{"admin": 1}');

INSERT INTO roles(`name`, `permissions`) VALUES('Dean', '{"dean": 1}');

INSERT INTO roles(`name`, `permissions`) VALUES('Staff', '{"staff": 1}');

INSERT INTO roles(`name`, `permissions`) VALUES('Guest', '{"guest": 1}');


// id_number = 0000
// Password = admin
INSERT INTO users(`id_number`, `password`, `first_name`, `middle_name`, `last_name`, `email`, `address`, `created_at`, `role_id`)
VALUES ('0000', '$2y$10$m1VHQVwQ4d/t4wJ3ucgO3O8KESFcSAas/zmbPuT2wDDqXO/IkNUSS', 'admin', '', 'user', 'admin@admin.com', '', NOW(), 1);