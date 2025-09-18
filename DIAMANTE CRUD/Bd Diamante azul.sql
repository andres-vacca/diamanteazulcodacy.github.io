Create Database Diamante_Azul;
Use Diamante_Azul;

Create table Rol (
    id_rol int auto_increment primary key not null,
    nombre_rol varchar(50) not null unique
);

Create table Usuario (
    id_usuario int auto_increment primary key not null,
    nombre_usuario varchar(60) not null unique,
    contrasena_usuario varchar(60) not null,
    tipo_documento_usuario ENUM('CC','TI','CE','PP','Otro'),
    documento_usuario varchar(20) not null unique,
    telefono_usuario varchar(20) not null,
    email_usuario varchar(60) not null,
    id_rol int not null,
    estado_usuario ENUM('ACTIVO', 'INACTIVO') DEFAULT 'ACTIVO',
    foreign key (id_rol) references Rol(id_rol)
);


Create table Producto (
    id_producto INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    nombre_producto VARCHAR(50) NOT NULL,
    descripcion_producto VARCHAR(300) NOT NULL,
    estado_producto ENUM('AGOTADO','DISPONIBLE') NOT NULL,
    precio_producto DECIMAL(10, 2) NOT NULL
);

Create table Empeño (
    id_empeño INT PRIMARY KEY NOT NULL,
    fecha_empeño DATE NOT NULL,
    estado_empeño ENUM('VIGENTE','VENCIDO','PAGADO') NOT NULL,
    interes DECIMAL(10, 2) NOT NULL,
    id_producto_fk INT NOT NULL,
    CONSTRAINT fk_prod_empeno FOREIGN KEY (id_producto_fk) REFERENCES Producto(id_producto),
    fecha_vencimiento DATE NOT NULL
);

Create table Factura_Venta (
    id_factura_venta INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    fecha_venta DATE NOT NULL,
    valor_total DECIMAL(10, 2) NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    iva DECIMAL(10, 2) NOT NULL,
    id_empleado_fk INT NOT NULL,
    CONSTRAINT fk_empleado_factura FOREIGN KEY (id_empleado_fk) REFERENCES Usuario(id_usuario),
    id_cliente_fk INT NOT NULL,
    CONSTRAINT fk_cliente_factura FOREIGN KEY (id_cliente_fk) REFERENCES Usuario(id_usuario),
    estado_factura ENUM('PAGADA','PENDIENTE','CANCELADA') NOT NULL
);

Create table Detalle_Factura_Venta (
    id_detalle_venta INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    id_producto_fk INT NOT NULL,
    CONSTRAINT fk_producto_detalle FOREIGN KEY (id_producto_fk) REFERENCES Producto(id_producto),
    id_factura_fk INT NOT NULL,
    CONSTRAINT fk_factura_detalle FOREIGN KEY (id_factura_fk) REFERENCES Factura_Venta(id_factura_venta),
    cantidad_producto INT NOT NULL,
    sub_total_producto DECIMAL(10, 2) NOT NULL
);

Create table Cuotas (
    id_cuota int auto_increment primary key not null,
    id_factura_fk int not null,
    CONSTRAINT fk_factura_cuota FOREIGN KEY (id_factura_fk) REFERENCES Factura_Venta(id_factura_venta),
    numero_cuota int not null,
    valor_cuota DECIMAL(10, 2) not null,
    fecha_vencimiento date not null,
    fecha_pago date,
    estado_cuota ENUM('PENDIENTE', 'PAGADA', 'ATRASADA') not null
);

-- Sintaxis SQL para insertar datos iniciales en la base de datos Diamante_Azul

-- 1. Insertar roles básicos
INSERT INTO Rol (nombre_rol) VALUES 
('Administrador'),
('Empleado'),
('Cliente');

-- 2. Insertar usuario administrador por defecto
-- Contraseña: admin123 (sin hash)
INSERT INTO Usuario (nombre_usuario, contrasena_usuario, tipo_documento_usuario, documento_usuario, telefono_usuario, email_usuario, id_rol) VALUES 
('admin', 'admin123', 'CC', '12345678', '3001234567', 'admin@diamanteazul.com', 1);

-- 3. Insertar usuario empleado de ejemplo
-- Contraseña: empleado123
INSERT INTO Usuario (nombre_usuario, contrasena_usuario, tipo_documento_usuario, documento_usuario, telefono_usuario, email_usuario, id_rol) VALUES 
('empleado1', 'empleado123', 'CC', '87654321', '3007654321', 'empleado@diamanteazul.com', 2);

-- 4. Insertar usuario cliente de ejemplo
-- Contraseña: cliente123
INSERT INTO Usuario (nombre_usuario, contrasena_usuario, tipo_documento_usuario, documento_usuario, telefono_usuario, email_usuario, id_rol) VALUES 
('cliente1', 'cliente123', 'CC', '11223344', '3009876543', 'cliente@email.com', 3);

-- 5. Insertar productos de ejemplo
INSERT INTO Producto (nombre_producto, descripcion_producto, estado_producto, precio_producto) VALUES 
('Anillo de Oro 18k', 'Anillo de oro de 18 quilates con diamante de 0.5 quilates', 'DISPONIBLE', 2500000.00),
('Collar de Plata', 'Collar de plata 925 con dije de corazón', 'DISPONIBLE', 350000.00),
('Laptop Dell Inspiron', 'Laptop Dell Inspiron 15 3000, Intel Core i5, 8GB RAM, 256GB SSD', 'DISPONIBLE', 1800000.00),
('iPhone 13', 'iPhone 13 128GB, color azul, en excelente estado', 'DISPONIBLE', 3200000.00),
('Taladro Bosch', 'Taladro percutor Bosch GSB 13 RE, 600W', 'DISPONIBLE', 280000.00),
('Cadena de Oro', 'Cadena de oro de 14k, 60cm de longitud', 'AGOTADO', 1200000.00);

-- 6. Insertar empeños de ejemplo
INSERT INTO Empeño (id_empeño, fecha_empeño, estado_empeño, interes, id_producto_fk, fecha_vencimiento) VALUES 
(1001, '2024-01-15', 'VIGENTE', 15.00, 1, '2024-04-15'),
(1002, '2024-02-01', 'VIGENTE', 12.00, 6, '2024-05-01');

-- 7. Insertar facturas de venta de ejemplo
INSERT INTO Factura_Venta (fecha_venta, valor_total, subtotal, iva, id_empleado_fk, id_cliente_fk, estado_factura) VALUES 
('2024-01-20', 378000.00, 350000.00, 28000.00, 2, 3, 'PAGADA'),
('2024-02-10', 302400.00, 280000.00, 22400.00, 2, 3, 'PAGADA');

-- 8. Insertar detalles de factura
INSERT INTO Detalle_Factura_Venta (id_producto_fk, id_factura_fk, cantidad_producto, sub_total_producto) VALUES 
(2, 1, 1, 350000.00),
(5, 2, 1, 280000.00);

-- 9. Insertar cuotas de ejemplo
INSERT INTO Cuotas (id_factura_fk, numero_cuota, valor_cuota, fecha_vencimiento, fecha_pago, estado_cuota) VALUES 
(1, 1, 378000.00, '2024-01-20', '2024-01-20', 'PAGADA'),
(2, 1, 302400.00, '2024-02-10', '2024-02-10', 'PAGADA');

-- CREDENCIALES DE ACCESO (SIN HASH):
-- 
-- ADMINISTRADOR:
-- Usuario: admin
-- Contraseña: admin123
-- 
-- EMPLEADO:
-- Usuario: empleado1
-- Contraseña: empleado123
-- 
-- CLIENTE:
-- Usuario: cliente1
-- Contraseña: cliente123
-- 
-- NOTA: Las contraseñas están almacenadas en texto plano para facilitar el acceso.