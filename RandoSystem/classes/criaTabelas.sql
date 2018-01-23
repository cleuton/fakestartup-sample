DROP TABLE IF EXISTS user_games;
DROP TABLE IF EXISTS game_user;
DROP TABLE IF EXISTS user_records;
DROP TABLE IF EXISTS absolute_records;
DROP TABLE IF EXISTS i18n;
DROP TABLE IF EXISTS levels;

CREATE TABLE game_user (
	id serial NOT NULL PRIMARY KEY,
	fbid bigint,
	nivel integer
);
CREATE INDEX game_user_fbid_index ON game_user USING btree (fbid);

CREATE TABLE user_games (
	id integer REFERENCES game_user (id),
	data timestamp with time zone DEFAULT now(),
	level_played integer,
	seconds integer, 
	PRIMARY KEY (id, data)
);

CREATE TABLE user_records (
	id integer REFERENCES game_user (id),
	level_played integer,
	seconds integer, 
	PRIMARY KEY (id, level_played)
);

CREATE TABLE absolute_records (
	level 	integer,
	id integer REFERENCES game_user (id),
	seconds	integer,
	PRIMARY KEY (level, id)
);

CREATE TABLE i18n (
  chave text,
  locale varchar(7) NOT NULL,
  traduzido text, 
  PRIMARY KEY (chave, locale)
);

INSERT INTO game_user (fbid, nivel) values (9055,1);
INSERT INTO user_games (id, level_played, seconds) values (1, 1, 100);

create table levels (
	number 	int,
	title  	varchar(100),
	games  	int,
	rows   	int,
	cols   	int,
	average	int,
	picture varchar(250),
	PRIMARY KEY (number)
);

insert into levels (number, title, games, rows, cols, average, picture) 
	   values (1, 'aprendiz', 2, 10, 10, 100, 'aprendiz');
insert into levels (number, title, games, rows, cols, average, picture) 
	   values (2, 'amador', 2, 13, 13, 100, 'amador');
insert into levels (number, title, games, rows, cols, average, picture) 
	   values (3, 'praticante', 2, 15, 15, 100, 'praticante');
insert into levels (number, title, games, rows, cols, average, picture) 
	   values (4, 'profissional', 1, 20, 20, 180, 'profissional');
insert into levels (number, title, games, rows, cols, average, picture) 
	   values (5, 'mestre', 0, 30, 30, 0, 'mestre');	   