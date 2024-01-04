/* Escribir los script sql que se ingresan a la base de datos*/
CREATE TABLE productos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  codigo VARCHAR(50) NOT NULL,
  nombre VARCHAR(100) NOT NULL,
  descripcion TEXT,
  precio_v DECIMAL(10, 2) NOT NULL,
  stock INT DEFAULT 0
);

CREATE TABLE compra_cabecera (
  id INT AUTO_INCREMENT PRIMARY KEY,
  fecha DATE,
  proveedor VARCHAR(100),
  num_factura VARCHAR(100),
  total DECIMAL (10, 2)
);

CREATE TABLE compra_detalle (
  id INT AUTO_INCREMENT PRIMARY KEY,
  cabecera_id INT,
  producto_codigo INT,
  precio_c DECIMAL(10, 2) NOT NULL,
  cantidad INT,
  FOREIGN KEY (cabecera_id) REFERENCES compra_cabecera(id),
  FOREIGN KEY (producto_codigo) REFERENCES productos(id)
);

/*==============================================================*/
/* Table: paquete_cabecera                                      */
/*==============================================================*/
create table paquete_cabecera (
  paquete_id int not null auto_increment,
  titulo_paquete varchar(500) not null,
  tipo_paquete int not null,
  numero_sesiones int not null,
  total decimal(10, 2) not null,
  primary key (paquete_id)
);

/*==============================================================*/
/* Table: paquete_detalle                                       */
/*==============================================================*/
create table paquete_detalle (
  paquete_id int,
  pro_ser_id int,
  nombre varchar(1240) not null,
  tipo varchar(100) not null,
  costo decimal(10, 2) not null,
  cantidad int not null,
  total decimal(10, 2) not null,
  FOREIGN KEY (paquete_id) REFERENCES paquete_cabecera (paquete_id)
);

/*==============================================================*/
/* Table: consultas_fisioterapeuta                     */
/*==============================================================*/
create table consultas_fisioterapeuta (
  consulta_fisio_id int not null auto_increment,
  paciente_id int,
  paquete_id int,
  fecha date,
  profesion varchar(500),
  tipo_trabajo varchar(500),
  sedestacion_prolongada int,
  esfuerzo_fisico int,
  habitos varchar(500),
  antecedentes_diagnostico varchar(500),
  tratamientos_anteriores varchar(500),
  contracturas varchar(500),
  irradiacion int,
  hacia_donde varchar(500),
  intensidad varchar(500),
  sensaciones varchar(500),
  limitacion_movilidad int,
  estado_atencion varchar(255),
  primary key (consulta_fisio_id),
  FOREIGN KEY (paciente_id) REFERENCES pacientes (id),
  FOREIGN KEY (paquete_id) REFERENCES paquete_cabecera (paquete_id)
);

/*==============================================================*/
/* Table: consultas_fisioterapeuta_detalle                      */
/*==============================================================*/
create table consultas_fisioterapeuta_detalle
(
   consulta_fisio_detalle_id  int not null auto_increment,
   consulta_fisio_id          int,
   usuario_id                 int,
   fecha                      date,
   electroestimulacion        boolean,
   ultrasonido                boolean,
   magnetoterapia             boolean,
   laserterapia               boolean,
   termoterapia               boolean,
   masoterapia                boolean,
   crioterapia                boolean,
   malibre                    boolean,
   maasistida                 boolean,
   fmuscular                  boolean,
   propiocepcion              boolean,
   epunta                     boolean,
   primary key (consulta_fisio_detalle_id),
   FOREIGN KEY (usuario_id) REFERENCES usuarios (id),
   FOREIGN KEY (consulta_fisio_id) REFERENCES consultas_fisioterapeuta (consulta_fisio_id)
);

CREATE TABLE ventas_cabecera (
  id INT AUTO_INCREMENT PRIMARY KEY,
  fecha_venta DATE,
  id_consulta int,
  id_paciente int,
  usuario VARCHAR(100),
  estado boolean,
  total DECIMAL(10, 2),
  FOREIGN KEY (id_consulta) REFERENCES consultas(id),
  FOREIGN KEY (id_paciente) REFERENCES pacientes(id)
);



CREATE TABLE ventas_detalle (
  detalle_id INT AUTO_INCREMENT PRIMARY KEY,
  venta_id INT NOT NULL,
  tipo_item ENUM('producto', 'servicio', 'paquete') NOT NULL,
  item_id INT NOT NULL,
  cantidad INT NOT NULL,
  precio_unitario DECIMAL(10, 2) NOT NULL,
  subtotal DECIMAL(10, 2) NOT NULL,
  FOREIGN KEY (venta_id) REFERENCES ventas_cabecera(id),
  CHECK (tipo_item IN ('producto', 'servicio', 'paquete'))
);




ALTER TABLE
  consultas
ADD
  estado VARCHAR(255);

CREATE TABLE servicios (
  id_servicio INT AUTO_INCREMENT PRIMARY KEY,
  titulo_servicio varchar(500),
  total decimal(10, 2) not null,
  valor_adicional decimal(10, 2) not null
);

  CREATE TABLE deatelle_servicio (
    id_det_servicio INT AUTO_INCREMENT PRIMARY KEY,
    id_servicio int,
    id_producto int,
    nombre VARCHAR(100) NOT NULL,
    precio DECIMAL(10, 2) NOT NULL,
    cantidad int,
    subtotal double,
    FOREIGN KEY (id_servicio) REFERENCES servicios(id_servicio),
    FOREIGN KEY (id_producto) REFERENCES productos(id)
  );
  ALTER TABLE consultas_fisioterapeuta ADD numero_sesiones INTEGER;


/* Nuevas inserciones de SQL */

alter table productos  add tipo boolean;
  alter table compra_cabecera  add num_factura varchar(225);



  create  table empresa (
id INT AUTO_INCREMENT PRIMARY KEY,
nombre VARCHAR(100) NOT NULL
)


alter table pacientes add  estado boolean;
alter table pacientes add  fk_id_empresa int;


  create  table empresa (
id INT AUTO_INCREMENT PRIMARY KEY,
nombre VARCHAR(100) NOT NULL
)

ALTER TABLE pacientes  FOREIGN KEY (fk_id_empresa) REFERENCES empresa(id);

  create  table empresa (
id INT AUTO_INCREMENT PRIMARY KEY,
nombre VARCHAR(100) NOT NULL
)

  create  table fuente (
id INT AUTO_INCREMENT PRIMARY KEY,
nombre VARCHAR(100) NOT NULL
)

alter table pacientes add  fk_id_fuente int;

ALTER TABLE pacientes  add FOREIGN KEY (fk_id_fuente) REFERENCES fuente(id);

alter  table servicios add sesiones   int;


ALTER TABLE consultas  ADD peso DECIMAL(8,2);
ALTER TABLE consultas  ADD talla DECIMAL(8,2);
ALTER TABLE consultas  ADD presion DECIMAL(8,2);
ALTER TABLE consultas  ADD saturacion DECIMAL(8,2);



------------------------------------
/* Base de datos  */

alter  table paquete_cabecera add ahorra decimal(10,2);

alter table consultas_fisioterapeuta  add  fk_id_cita int;


ALTER TABLE consultas_fisioterapeuta
ADD FOREIGN KEY (fk_id_cita) REFERENCES citas(id);

alter table consultas_fisioterapeuta  add  total_sesiones int;

alter table consultas_fisioterapeuta_detalle  add  fk_id_cita int;


ALTER TABLE consultas_fisioterapeuta_detalle
ADD FOREIGN KEY (fk_id_cita) REFERENCES citas(id);


/* Agregar campos a las vistas consultas_datos  */
 `c`.`peso` as `peso`,
    `c`.`talla` as `talla`,
    `c`.`saturacion` as `saturacion`,
    `c`.`presion` as `presion`,



create  table receta (
id INT AUTO_INCREMENT PRIMARY KEY,
receta  VARCHAR(255),
indicaciones  VARCHAR(255),
id_cita INT,
FOREIGN KEY (id_cita) REFERENCES citas(id)
);



-- villame5_bb01.consulta_receta source

create  view `consulta_receta` as
select
    `r`.`id` as `id_receta`,
    `r`.`receta` as `receta`,
    `r`.`indicaciones` as `indicaciones`,
    `c`.`motivo_consulta` as `motivo_consulta`,
    `c`.`fecha_hora` as `fecha_hora`,
    `c`.`diagnostico` as `diagnostico`,
    `p`.`nombres` as `nombres_paciente`,
    `p`.`apellidos` as `apellidos_paciente`,
    `u`.`id` as `id_doctor`,
    `u`.`nombre` as `nombre_doctor`,
    `u`.`apellidos` as `apellido_doctor`
from
    ((((`receta` `r`
join `consultas` `c` on
    (`r`.`id_cita` = `c`.`id_cita`))
join `pacientes` `p` on
    (`c`.`id_paciente` = `p`.`id`))
join `citas` `ci` on
    (`r`.`id_cita` = `ci`.`id`))
join `usuarios` `u` on
    (`ci`.`id_doctor` = `u`.`id`));



alter table ventas_cabecera  add descuento decimal(10,2);


alter table consultas_fisioterapeuta_detalle drop column fecha;

alter table consultas_fisioterapeuta_detalle add  fecha DATETIME;

alter table ventas_cabecera add id_user integer;
ALTER TABLE ventas_cabecera
ADD FOREIGN KEY (id_user) REFERENCES usuarios(id);




/**/

create or replace
algorithm = UNDEFINED view `consulta_fisio` as
select
    `p`.`nombres` as `nombre_paciente`,
    `p`.`apellidos` as `apellidos_paciente`,
    `p`.`numero_identidad` as `cedula`,
    `pa`.`titulo_paquete` as `nombre_paquete`,
    `pa`.`numero_sesiones` as `sesiones_t`,
    `cf`.`numero_sesiones` as `sesiones`,
    `cf`.`consulta_fisio_id` as `id_fisi`,
    `cf`.`total_sesiones` as `total_sesiones`
from
    ((`consultas_fisioterapeuta` `cf`
join `pacientes` `p` on
    (`cf`.`paciente_id` = `p`.`id`))
join `paquete_cabecera` `pa` on
    (`cf`.`paquete_id` = `pa`.`paquete_id`));

/* Citas datos */
`u`.`rol` as `rol`,



/* Cambios en el servidor */

create  table receta_fisio (
id INT AUTO_INCREMENT PRIMARY KEY,
examenes  VARCHAR(255),
tratamiento  VARCHAR(255),
id_cita INT,
FOREIGN KEY (id_cita) REFERENCES citas(id)
);




/* Vista */


CREATE VIEW `consulta_receta_fisio` AS
select
    `r`.`id` AS `id_receta`,
    `r`.`examenes` AS `examenes`,
    `r`.`tratamiento` AS `tratamiento`,
    `c`.`motivo_consulta` AS `motivo_consulta`,
    `c`.`fecha_hora` AS `fecha_hora`,
    `c`.`diagnostico` AS `diagnostico`,
    `p`.`nombres` AS `nombres_paciente`,
    `p`.`apellidos` AS `apellidos_paciente`,
    `u`.`id` AS `id_doctor`,
    `p`.`id` AS `id_paciente`,
    `u`.`nombre` AS `nombre_doctor`,
    `u`.`apellidos` AS `apellido_doctor`
from
    ((((`receta_fisio` `r`
join `consultas` `c` on
    (`r`.`id_cita` = `c`.`id_cita`))
join `pacientes` `p` on
    (`c`.`id_paciente` = `p`.`id`))
join `citas` `ci` on
    (`r`.`id_cita` = `ci`.`id`))
join `usuarios` `u` on
    (`ci`.`id_doctor` = `u`.`id`));



/* Add */

alter table pacientes add institucion varchar(255);
alter table pacientes add descripcion varchar(255);
alter table pacientes add tipo_contingencia varchar(255);

/* Agregar datos a vista  */

`p`.`ocupacion` AS `ocupacion`,
    `p`.`institucion` AS `institucion`,
    `p`.`descripcion` AS `descripcion`,
    `p`.`tipo_contingencia` AS `tipo_contingencia`,


alter table consultas  add hea varchar(255);



/* Empezar desde cero */


/* Cargar inventario */
delete from  ventas_detalle ;
delete from  ventas_cabecera ;
delete from compra_detalle ;
delete from compra_cabecera;
delete from productos ;






/* Nuevos cambios */


-- prueba.consulta_fisio source
/* Agregar un nueva dato de consuta id_paciente */
create or replace
algorithm = UNDEFINED view `consulta_fisio` as
select
	`p`.`id` as `id_paciente`,    
	`p`.`nombres` as `nombre_paciente`,
    `p`.`apellidos` as `apellidos_paciente`,
    `p`.`numero_identidad` as `cedula`,
    `pa`.`titulo_paquete` as `nombre_paquete`,
    `pa`.`numero_sesiones` as `sesiones_t`,
    `cf`.`numero_sesiones` as `sesiones`,
    `cf`.`consulta_fisio_id` as `id_fisi`,
    `cf`.`total_sesiones` as `total_sesiones`
from
    ((`consultas_fisioterapeuta` `cf`
join `pacientes` `p` on
    (`cf`.`paciente_id` = `p`.`id`))
join `paquete_cabecera` `pa` on
    (`cf`.`paquete_id` = `pa`.`paquete_id`));




    alter table consultas_fisioterapeuta add motivo_consulta varchar(255);



/* Nuevos cambios */

