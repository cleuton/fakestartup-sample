--
-- PostgreSQL database dump
--

-- Started on 2012-09-04 08:26:21

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;

SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

CREATE TABLE absolute_records (
    level integer NOT NULL,
    id integer NOT NULL,
    seconds integer
);


CREATE TABLE game_user (
    id integer NOT NULL,
    fbid bigint,
    nivel integer
);


CREATE SEQUENCE game_user_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER SEQUENCE game_user_id_seq OWNED BY game_user.id;


SELECT pg_catalog.setval('game_user_id_seq', 1, true);


CREATE TABLE i18n (
    chave text NOT NULL,
    locale character varying(7) NOT NULL,
    traduzido text
);



CREATE TABLE levels (
    number integer NOT NULL,
    title character varying(100),
    games integer,
    rows integer,
    cols integer,
    average integer,
    picture character varying(250)
);



CREATE TABLE user_games (
    id integer NOT NULL,
    data timestamp with time zone DEFAULT now() NOT NULL,
    level_played integer,
    seconds integer
);



CREATE TABLE user_records (
    id integer NOT NULL,
    level_played integer NOT NULL,
    seconds integer
);


ALTER TABLE ONLY game_user ALTER COLUMN id SET DEFAULT nextval('game_user_id_seq'::regclass);


ALTER TABLE ONLY absolute_records
    ADD CONSTRAINT absolute_records_pkey PRIMARY KEY (level, id);



ALTER TABLE ONLY game_user
    ADD CONSTRAINT game_user_pkey PRIMARY KEY (id);



ALTER TABLE ONLY i18n
    ADD CONSTRAINT i18n_pkey PRIMARY KEY (chave, locale);




ALTER TABLE ONLY levels
    ADD CONSTRAINT levels_pkey PRIMARY KEY (number);



ALTER TABLE ONLY user_games
    ADD CONSTRAINT user_games_pkey PRIMARY KEY (id, data);



ALTER TABLE ONLY user_records
    ADD CONSTRAINT user_records_pkey PRIMARY KEY (id, level_played);




CREATE INDEX game_user_fbid_index ON game_user USING btree (fbid);



ALTER TABLE ONLY absolute_records
    ADD CONSTRAINT absolute_records_id_fkey FOREIGN KEY (id) REFERENCES game_user(id);


ALTER TABLE ONLY user_games
    ADD CONSTRAINT user_games_id_fkey FOREIGN KEY (id) REFERENCES game_user(id);



ALTER TABLE ONLY user_records
    ADD CONSTRAINT user_records_id_fkey FOREIGN KEY (id) REFERENCES game_user(id);



INSERT INTO game_user (id, fbid, nivel) VALUES (1, 9055, 5);

INSERT INTO absolute_records (level, id, seconds) VALUES (1, 1, 8);
INSERT INTO absolute_records (level, id, seconds) VALUES (2, 1, 15);
INSERT INTO absolute_records (level, id, seconds) VALUES (3, 1, 14);
INSERT INTO absolute_records (level, id, seconds) VALUES (4, 1, 81);



INSERT INTO i18n (chave, locale, traduzido) VALUES ('titulo', 'pt', 'Ajude os memes a sairem do labirinto!');
INSERT INTO i18n (chave, locale, traduzido) VALUES ('ultimonivel', 'pt', 'Voc&ecirc; j&aacute; est&aacute; no &uacute;ltimo n&iacute;vel.');
INSERT INTO i18n (chave, locale, traduzido) VALUES ('novo', 'pt', 'Novo');
INSERT INTO i18n (chave, locale, traduzido) VALUES ('novo', 'en', 'New');
INSERT INTO i18n (chave, locale, traduzido) VALUES ('desisto', 'pt', 'Desisto!');
INSERT INTO i18n (chave, locale, traduzido) VALUES ('desisto', 'en', 'I give up!');
INSERT INTO i18n (chave, locale, traduzido) VALUES ('ajuda', 'pt', 'Ajuda');
INSERT INTO i18n (chave, locale, traduzido) VALUES ('ajuda', 'en', 'Help');
INSERT INTO i18n (chave, locale, traduzido) VALUES ('sobre', 'pt', 'Sobre');
INSERT INTO i18n (chave, locale, traduzido) VALUES ('sobre', 'en', 'About');
INSERT INTO i18n (chave, locale, traduzido) VALUES ('ultimonivel', 'en', 'You are at the last level.');
INSERT INTO i18n (chave, locale, traduzido) VALUES ('selecnivel', 'en', 'Your current level is @1. Which level do you want to play?');
INSERT INTO i18n (chave, locale, traduzido) VALUES ('aprendiz', 'pt', 'Aprendiz');
INSERT INTO i18n (chave, locale, traduzido) VALUES ('aprendiz', 'en', 'Apprentice');
INSERT INTO i18n (chave, locale, traduzido) VALUES ('amador', 'pt', 'Amador');
INSERT INTO i18n (chave, locale, traduzido) VALUES ('amador', 'en', 'Amateur');
INSERT INTO i18n (chave, locale, traduzido) VALUES ('praticante', 'pt', 'Praticante');
INSERT INTO i18n (chave, locale, traduzido) VALUES ('praticante', 'en', 'Practitioner');
INSERT INTO i18n (chave, locale, traduzido) VALUES ('profissional', 'pt', 'Profissional');
INSERT INTO i18n (chave, locale, traduzido) VALUES ('profissional', 'en', 'Professional');
INSERT INTO i18n (chave, locale, traduzido) VALUES ('mestre', 'pt', 'Mestre');
INSERT INTO i18n (chave, locale, traduzido) VALUES ('mestre', 'en', 'Master');
INSERT INTO i18n (chave, locale, traduzido) VALUES ('titulo', 'en', 'Help memes to get out of the maze!');
INSERT INTO i18n (chave, locale, traduzido) VALUES ('selecnivel', 'pt', 'Seu n&iacute;vel atual &eacute; @1. Qual n&iacute;vel quer jogar?');
INSERT INTO i18n (chave, locale, traduzido) VALUES ('desistirmsg', 'pt', 'Bem, mais sorte da pr&oacute;xima vez!');
INSERT INTO i18n (chave, locale, traduzido) VALUES ('desistirmsg', 'en', 'Well, Better luck next time!');
INSERT INTO i18n (chave, locale, traduzido) VALUES ('tempo', 'pt', 'Tempo: ');
INSERT INTO i18n (chave, locale, traduzido) VALUES ('tempo', 'en', 'Time: ');
INSERT INTO i18n (chave, locale, traduzido) VALUES ('msgfinal', 'en', '. Your score was recorded!');
INSERT INTO i18n (chave, locale, traduzido) VALUES ('msgfinal', 'pt', '. Seu resultado foi gravado!');
INSERT INTO i18n (chave, locale, traduzido) VALUES ('msgmudou', 'pt', '<br/>Parab&eacute;ns! Voc&ecirc; mudou de n&iacute;vel.');
INSERT INTO i18n (chave, locale, traduzido) VALUES ('msgmudou', 'en', '<br/>Congratulations! Have you changed your level.');
INSERT INTO i18n (chave, locale, traduzido) VALUES ('msgrecord', 'pt', ' Voc&ecirc; quebrou records! Verifique seus records e o hall da fama!');
INSERT INTO i18n (chave, locale, traduzido) VALUES ('msgrecord', 'en', ' You broke records! Check your records and the "hall" of fame!');
INSERT INTO i18n (chave, locale, traduzido) VALUES ('infonivel', 'pt', 'N&uacute;mero de jogos para mudar de n&iacute;vel: @1');
INSERT INTO i18n (chave, locale, traduzido) VALUES ('infonivel', 'en', 'Number of games to change level: @1');
INSERT INTO i18n (chave, locale, traduzido) VALUES ('nivelatual', 'en', 'Your current level is: @1');
INSERT INTO i18n (chave, locale, traduzido) VALUES ('outro', 'pt', 'de outro fornecedor');
INSERT INTO i18n (chave, locale, traduzido) VALUES ('outro', 'en', 'from other supplier');
INSERT INTO i18n (chave, locale, traduzido) VALUES ('nivelatual', 'pt', 'Seu n&iacute;vel atual &eacute;: @1');
INSERT INTO i18n (chave, locale, traduzido) VALUES ('media', 'pt', 'Sua m&eacute;dia &eacute;: @1.');
INSERT INTO i18n (chave, locale, traduzido) VALUES ('media', 'en', 'Your average is: @1.');
INSERT INTO i18n (chave, locale, traduzido) VALUES ('mediamudar', 'pt', 'A m&eacute;dia para mudar de n&iacute;vel &eacute;: @1.');
INSERT INTO i18n (chave, locale, traduzido) VALUES ('mediamudar', 'en', 'Average time required to change level: @1;');
INSERT INTO i18n (chave, locale, traduzido) VALUES ('seusrecords', 'pt', 'Seus recordes.');
INSERT INTO i18n (chave, locale, traduzido) VALUES ('seusrecords', 'en', 'Your records.');
INSERT INTO i18n (chave, locale, traduzido) VALUES ('hall', 'pt', 'Galeria da fama.');
INSERT INTO i18n (chave, locale, traduzido) VALUES ('hall', 'en', 'Hall of fame.');
INSERT INTO i18n (chave, locale, traduzido) VALUES ('seminformacoes', 'pt', 'Sem informa&ccedil;&otilde;es.');
INSERT INTO i18n (chave, locale, traduzido) VALUES ('seminformacoes', 'en', 'No records.');



INSERT INTO levels (number, title, games, rows, cols, average, picture) VALUES (1, 'aprendiz', 2, 10, 10, 100, 'aprendiz');
INSERT INTO levels (number, title, games, rows, cols, average, picture) VALUES (2, 'amador', 2, 13, 13, 100, 'amador');
INSERT INTO levels (number, title, games, rows, cols, average, picture) VALUES (3, 'praticante', 2, 15, 15, 100, 'praticante');
INSERT INTO levels (number, title, games, rows, cols, average, picture) VALUES (4, 'profissional', 1, 20, 20, 180, 'profissional');
INSERT INTO levels (number, title, games, rows, cols, average, picture) VALUES (5, 'mestre', 0, 30, 30, 0, 'mestre');


INSERT INTO user_games (id, data, level_played, seconds) VALUES (1, '2012-08-31 08:00:27.531-03', 2, 38);
INSERT INTO user_games (id, data, level_played, seconds) VALUES (1, '2012-08-31 08:01:31.867-03', 2, 42);
INSERT INTO user_games (id, data, level_played, seconds) VALUES (1, '2012-08-31 08:03:15.749-03', 3, 26);
INSERT INTO user_games (id, data, level_played, seconds) VALUES (1, '2012-08-31 10:20:18.307-03', 3, 43);
INSERT INTO user_games (id, data, level_played, seconds) VALUES (1, '2012-08-31 15:59:57.098-03', 1, 9);
INSERT INTO user_games (id, data, level_played, seconds) VALUES (1, '2012-09-03 11:08:52.581-03', 1, 23);
INSERT INTO user_games (id, data, level_played, seconds) VALUES (1, '2012-09-03 14:39:50.438-03', 4, 81);



INSERT INTO user_records (id, level_played, seconds) VALUES (1, 2, 15);
INSERT INTO user_records (id, level_played, seconds) VALUES (1, 3, 14);
INSERT INTO user_records (id, level_played, seconds) VALUES (1, 1, 9);
INSERT INTO user_records (id, level_played, seconds) VALUES (1, 4, 81);




