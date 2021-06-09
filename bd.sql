DROP DATABASE IF EXISTS sw14_internal;
CREATE DATABASE sw14_internal; 
USE sw14_internal;

CREATE TABLE tb_careers( 
  id_career  INT PRIMARY KEY AUTO_INCREMENT,
  name_career VARCHAR(80),
  status_career enum('Activo','Inactivo') DEFAULT 'Activo' NOT NULL,
  created_at_career TIMESTAMP
);

CREATE TABLE tb_persons(
  id_person INT PRIMARY KEY AUTO_INCREMENT,
  name_person VARCHAR(80),
  lastname_person VARCHAR(80),
  gender_person enum('F','M'),
  email_person VARCHAR(80),
  identifier_person VARCHAR(80),
  phone_person VARCHAR(80),
  role_person enum ('Almacenista', 'Personal académico/administrativo', 'Estudiante', 'Otros'),
  person_career INT,
  created_at_person TIMESTAMP,
  FOREIGN KEY(person_career) REFERENCES tb_careers(id_career)
);

CREATE TABLE tb_users(
  id_user INT PRIMARY KEY AUTO_INCREMENT, 
  email_user VARCHAR(80), 
  password_user VARCHAR(80),
  created_at TIMESTAMP,
  user_person INT,
  FOREIGN KEY(user_person) REFERENCES tb_persons(id_person)
);

CREATE TABLE tb_materials(
  id_material INT PRIMARY KEY AUTO_INCREMENT,
  name_material VARCHAR(80),
  desc_material TEXT,
  use_material TEXT,
  penal_material TEXT,
  img_material TEXT,
  type_material enum('Múltiple', 'Consumible', 'Herramienta'),
  stock_material INT
);

CREATE TABLE tb_folios_materials(
  id_folio INT PRIMARY KEY AUTO_INCREMENT,
  text_folio VARCHAR(80),
  qr_folio TEXT,
  status_folio enum('Disponible', 'En Préstamo', 'En Mantenimiento'),
  folio_material INT,
  FOREIGN KEY(folio_material) REFERENCES tb_materials(id_material)
);

CREATE TABLE tb_requests(
  id_request INT PRIMARY KEY AUTO_INCREMENT,
  date_request DATETIME,
  date_attention_request DATETIME,
  status_request enum('Pendiente','Autorizada', 'Denegada', 'Cancelada'),
  request_person INT,
  attention_person INT,
  FOREIGN KEY(request_person) REFERENCES tb_persons(id_person),
  FOREIGN KEY(attention_person) REFERENCES tb_persons(id_person)
);

CREATE TABLE tb_requests_detail(
  id_detail INT PRIMARY KEY AUTO_INCREMENT,
  detail_material INT,
  detail_request INT,
  quantity_detail INT,
  status_detail enum('Entregado', 'Pendiente de Entrega', 'No Disponible'),
  FOREIGN KEY(detail_request) REFERENCES tb_requests(id_request),
  FOREIGN KEY(detail_material) REFERENCES tb_materials(id_material)
);

CREATE TABLE tb_loans(
  id_loan INT PRIMARY KEY AUTO_INCREMENT,
  date_loan DATETIME,
  loan_person INT,
  loan_request INT,
  loan_folio INT,
  FOREIGN KEY(loan_person) REFERENCES tb_persons(id_person),
  FOREIGN KEY(loan_request) REFERENCES tb_requests(id_request),
  FOREIGN KEY(loan_folio) REFERENCES tb_folios_materials(id_folio)
);

CREATE TABLE tb_material_returns(
  id_return INT PRIMARY KEY AUTO_INCREMENT,
  return_loan INT,
  date_return DATETIME,
  return_person INT,
  notes_return TEXT,
  FOREIGN KEY(return_loan) REFERENCES tb_loans(id_loan),
  FOREIGN KEY(return_person) REFERENCES tb_persons(id_person)
);

