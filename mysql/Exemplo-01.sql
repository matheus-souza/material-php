use dbphp7;

CREATE TABLE tb_usuarios(
	idusuario INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    deslogin VARCHAR(64) NOT NULL,
    dessenha varchar(256) not null,
    dtcadastro timestamp not null default current_timestamp());
    
    insert into tb_usuarios(deslogin, dessenha) values('root', 'qwe123');
    
    select * from tb_usuarios;
    
    update tb_usuarios set dessenha='123456' where idusuario=1;
    
    delete from tb_usuarios where idusuario=2;
    
    truncate table tb_usuarios;
    
    