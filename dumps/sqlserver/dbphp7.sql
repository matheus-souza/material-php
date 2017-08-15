CREATE DATABASE dbphp7
GO
USE dbphp7
GO
CREATE TABLE tb_usuarios(
  idusuario INT NOT NULL IDENTITY(1,1) PRIMARY KEY,
  deslogin VARCHAR(64) NOT NULL,
  dessenha VARCHAR(256) NOT NULL,
  dtcadastro DATETIME default CURRENT_TIMESTAMP
)
GO
INSERT INTO tb_usuarios(deslogin, dessenha) VALUES('root', 'qwe123');
GO