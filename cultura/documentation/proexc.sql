-- phpMyAdmin SQL Dump
-- version 2.11.4
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tempo de Geração: Set 04, 2008 as 12:54 
-- Versão do Servidor: 5.0.51
-- Versão do PHP: 5.2.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Banco de Dados: `proexc`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `agenda`
--

CREATE TABLE IF NOT EXISTS `agenda` (
  `id` int(11) NOT NULL auto_increment,
  `titulo` varchar(255) NOT NULL,
  `tituloImagem` varchar(255) default NULL,
  `descricao` text NOT NULL,
  `ativo` tinyint(1) NOT NULL default '1',
  `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `login` char(20) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `login` (`login`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Extraindo dados da tabela `agenda`
--

INSERT INTO `agenda` (`id`, `titulo`, `tituloImagem`, `descricao`, `ativo`, `timestamp`, `login`) VALUES
(1, 'Noticia 1', NULL, '<p>Lista:</p>\r\n<ol>\r\n    <li>asdasd</li>\r\n    <li>asdasd</li>\r\n    <li>asdasd</li>\r\n</ol>', 1, '2008-06-11 16:00:16', 'admin'),
(2, 'noticia 2', '', '<p><strong>sdasdasdasd</strong></p>\r\n<ol>\r\n    <li>asdasdasdas</li>\r\n    <li>dasdasdasdas</li>\r\n</ol>\r\n<p style="text-align: center;">dasdsadasdasdasdas</p>', 0, '2008-06-11 16:00:16', 'admin'),
(3, 'noticia 3', NULL, '<ul>\r\n    <li>6546545</li>\r\n    <li>46464564</li>\r\n    <li>64645654</li>\r\n    <li>456464</li>\r\n    <li>46464654</li>\r\n</ul>', 1, '2008-06-11 16:00:16', 'admin'),
(4, 'noticia 4', NULL, '<p style="text-align: center;"><strong>asdsadsadasd</strong></p>\r\n<ol>\r\n    <li>65465</li>\r\n    <li>6546</li>\r\n    <li>65465</li>\r\n</ol>', 1, '2008-06-11 16:00:16', 'admin'),
(5, 'titulo', NULL, '<p>aasdasdasd</p>\r\n<ol>\r\n    <li>asdasdas</li>\r\n    <li>asdasdas</li>\r\n</ol>\r\n<p>&nbsp;</p>', 1, '2008-06-11 16:00:16', 'admin'),
(6, 'Inverno', NULL, '<p><img width="200" height="150" src="/userfiles/image/Inverno.jpg" alt="" /></p>', 1, '2008-06-11 16:00:16', 'admin'),
(7, 'Montanhas Azuis', 'NULL', '<p><img width="100" height="75" src="/userfiles/image/Montanhas%20azuis.jpg" alt="" /></p>', 0, '2008-06-11 16:00:16', 'admin'),
(8, 'teste', '/userfiles/image/Inverno.jpg', '<p>asdasdas</p>', 1, '2008-06-11 16:00:16', 'admin'),
(9, 'sadasd', '/userfiles/image/Ninf%C3%83%C2%A9ias.jpg', '<p>sadasdas</p>', 0, '2008-06-11 16:00:16', 'admin'),
(10, 'teste', NULL, '<p>teste</p>', 0, '2008-06-11 16:26:20', 'teste');

-- --------------------------------------------------------

--
-- Estrutura da tabela `areatematica`
--

CREATE TABLE IF NOT EXISTS `areatematica` (
  `id` int(11) NOT NULL auto_increment,
  `nome` varchar(255) collate utf8_unicode_ci NOT NULL,
  `login` char(20) character set utf8 NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `login` (`login`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='InnoDB free: 10240 kB' AUTO_INCREMENT=10 ;

--
-- Extraindo dados da tabela `areatematica`
--

INSERT INTO `areatematica` (`id`, `nome`, `login`) VALUES
(1, 'ComunicaÃ§Ã£o', 'admin'),
(2, 'Cultura', 'admin'),
(3, 'SaÃºde', 'admin'),
(5, 'Direitos Humanos e JustiÃ§a', 'admin'),
(6, 'EducaÃ§Ã£o', 'admin'),
(7, 'Meio Ambiente', 'admin'),
(8, 'Tecnologia e ProduÃ§Ã£o', 'admin'),
(9, 'Trabalho', 'teste');

-- --------------------------------------------------------

--
-- Estrutura da tabela `colaboradordocente`
--

CREATE TABLE IF NOT EXISTS `colaboradordocente` (
  `id` int(11) NOT NULL auto_increment,
  `siape` char(7) NOT NULL,
  `nome` varchar(255) NOT NULL default '',
  `idDepartamento` int(11) NOT NULL,
  `telefone` char(14) NOT NULL default '',
  `celular` char(14) default NULL,
  `email` varchar(255) NOT NULL default '',
  `cargaHorariaSemanal` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `idDepartamento` (`idDepartamento`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 3072 kB; (`idDepartamento`) REFER `proexc/depar' AUTO_INCREMENT=13 ;

--
-- Extraindo dados da tabela `colaboradordocente`
--

INSERT INTO `colaboradordocente` (`id`, `siape`, `nome`, `idDepartamento`, `telefone`, `celular`, `email`, `cargaHorariaSemanal`) VALUES
(2, '', 'mmmmmmmmmmm', 1, '', NULL, '', NULL),
(3, '', 'nnnnnnn', 1, '', NULL, '', NULL),
(5, '', 'oooo', 1, '', NULL, '', NULL),
(6, '', 'colaborador docente um', 1, '(32)3232-3232', NULL, 'dsasd@dads.com', NULL),
(7, '', 'colaborador docente dois', 1, '(32)3232-3232', NULL, 'asdaD@dsad.com', NULL),
(8, '', 'bÃ¡', 1, '(43)4343-4343', NULL, 'dadsa@dasds.com', NULL),
(9, '', 'sadasd', 1, '(32)3232-3232', NULL, 'dasda@dads.com', NULL),
(10, '', 'a', 1, '(32)3232-3232', '', 'a@aa.com', 10),
(11, '', 'dsadasd', 1, '(32)3232-3232', '(32)3232-3232', 'adas@dsad.com', 10),
(12, '', 'asdasda', 1, '(32)3232-3232', '(32)3232-3232', 'dasda@dasda.com', 10);

-- --------------------------------------------------------

--
-- Estrutura da tabela `colaboradorexterno`
--

CREATE TABLE IF NOT EXISTS `colaboradorexterno` (
  `id` int(11) NOT NULL auto_increment,
  `nome` varchar(255) NOT NULL default '',
  `cpf` char(11) NOT NULL default '',
  `telefone` char(14) NOT NULL default '',
  `celular` char(14) default NULL,
  `email` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 3072 kB' AUTO_INCREMENT=7 ;

--
-- Extraindo dados da tabela `colaboradorexterno`
--

INSERT INTO `colaboradorexterno` (`id`, `nome`, `cpf`, `telefone`, `celular`, `email`) VALUES
(1, 'aaaa', '', '', NULL, ''),
(2, 'dasfasdfsd', '', '(32)3232-3232', NULL, 'dasdas@dads.com'),
(3, 'colaborador externo dois', '231321', '(32)3232-3232', NULL, 'sadasd@dasda.com'),
(4, 'dsadas', '54654654', '(32)3232-3232', NULL, 'sadasd@dads.com'),
(5, 'asdasdas', '2131321', '(32)3232-3232', '(32)3232-3232', 'dasdad@dads.com'),
(6, 'zczxczxc', '213123', '(32)3232-3232', '', 'asdasd@dsada.com');

-- --------------------------------------------------------

--
-- Estrutura da tabela `convenio`
--

CREATE TABLE IF NOT EXISTS `convenio` (
  `id` int(11) NOT NULL auto_increment,
  `nome` varchar(255) NOT NULL,
  `registro` char(8) NOT NULL,
  `descricao` text,
  `dataInicio` date NOT NULL,
  `dataFinal` date default NULL,
  `login` char(20) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `login` (`login`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Extraindo dados da tabela `convenio`
--

INSERT INTO `convenio` (`id`, `nome`, `registro`, `descricao`, `dataInicio`, `dataFinal`, `login`) VALUES
(1, 'A.L. Mister Shopping', '96.01.25', 'Estabelecer intercÃ¢mbio, em mÃºtua colaboraÃ§Ã£o, por servidores docentes e tÃ©cnicos-administrativos, discentes e administradores da UFJF, quanto do A. L. Mister Shopping, no que couber, com vistas ao desenvolvimento do ensino, da pesquisa, da extensÃ£o, dos serviÃ§os e da administraÃ§Ã£o.', '1996-03-06', NULL, 'admin'),
(2, 'Academia Gold Ltda', '96.07.24', 'Estabelecer intercÃ¢mbio, em mÃºtua colaboraÃ§Ã£o, por servidores docentes e tÃ©cnicos-administrativos, discentes e administradores da UFJF, quanto da Academia Gold Ltda, no que couber, com vistas ao desenvolvimento do ensino, da pesquisa, da extensÃ£o, dos serviÃ§os e da administraÃ§Ã£o.\r\n-1Âº Termo Aditivo - concessÃ£o de estÃ¡gio.\r\n', '1996-10-23', NULL, 'admin'),
(3, 'Centro de InvestigaÃ§Ã£o e DiagnÃ³stico em Anatomia PatolÃ³gica Ltda', '00.00.00', 'Visando abertura de campo para treinamento em ResidÃªncia MÃ©dica - Ã¡rea de concentraÃ§Ã£o em Patologia, para residentes da Universidade.Â \r\n', '2004-03-29', '2009-03-28', 'admin'),
(4, 'teste', 'teste', 'testetes', '2008-06-11', '2008-06-12', 'teste');

-- --------------------------------------------------------

--
-- Estrutura da tabela `coordenador`
--

CREATE TABLE IF NOT EXISTS `coordenador` (
  `id` int(11) NOT NULL auto_increment,
  `siape` char(7) NOT NULL default '',
  `nome` varchar(255) NOT NULL default '',
  `idTitulacao` int(11) default NULL,
  `idDepartamento` int(11) NOT NULL,
  `telefone` char(14) default NULL,
  `telefonePublico` char(14) NOT NULL default '',
  `celular` char(14) default NULL,
  `email` varchar(255) NOT NULL default '',
  `cargaHorariaSemanal` int(11) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `siape` (`siape`),
  KEY `idDepartamento` (`idDepartamento`),
  KEY `idTitulacao` (`idTitulacao`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 3072 kB; (`idDepartamento`) REFER `proexc/depar' AUTO_INCREMENT=21 ;

--
-- Extraindo dados da tabela `coordenador`
--

INSERT INTO `coordenador` (`id`, `siape`, `nome`, `idTitulacao`, `idDepartamento`, `telefone`, `telefonePublico`, `celular`, `email`, `cargaHorariaSemanal`) VALUES
(15, '1146431', 'teste', 2, 1, '32323232', '32323232', NULL, 'asasa', NULL),
(17, '1146435', 'teste', 1, 1, '(32)3232-3232', '(32)3232-3232', NULL, 'sdadas@dasd.com', NULL),
(18, '1146572', 'Maria Lucia', 1, 1, '(32)3232-3232', '(32)3232-3232', NULL, 'dadasd@dasdas.com', NULL),
(19, '1146430', 'teste', 1, 1, '(32)3232-3232', '(32)2102-3311', '(32)8822-1174', 'dadas@dasda.com', 10),
(20, 'equipe', 'Equipe Proexc', 1, 1, '(32)2102-3971', '(32)2102-3971', '(32)2102-3971', 'sadsa@dasd.com', 40);

-- --------------------------------------------------------

--
-- Estrutura da tabela `curso`
--

CREATE TABLE IF NOT EXISTS `curso` (
  `id` int(11) NOT NULL auto_increment,
  `titulo` varchar(255) NOT NULL,
  `processo` char(13) default NULL,
  `dataInicio` date default NULL,
  `dataFinal` date default NULL,
  `horario` varchar(255) default NULL,
  `cargaHoraria` int(11) default NULL,
  `local` varchar(255) default NULL,
  `idAreaTematica` int(11) default NULL,
  `idCoordenador` int(11) default NULL,
  `idViceCoordenador` int(11) default NULL,
  `coordenadorArea` varchar(255) default NULL,
  `publicoAlvo` text,
  `expectativaPublico` int(11) default NULL,
  `descricao` text,
  `resumo` text,
  `idRecursos` int(11) default NULL,
  `validado` tinyint(1) NOT NULL default '0',
  `modificadoEm` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `fechado` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idAreaTematica` (`idAreaTematica`),
  KEY `idCoordenador` (`idCoordenador`),
  KEY `idRecursos` (`idRecursos`),
  KEY `idViceCoordenador` (`idViceCoordenador`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Tabela de informações sobre cursos' AUTO_INCREMENT=3 ;

--
-- Extraindo dados da tabela `curso`
--

INSERT INTO `curso` (`id`, `titulo`, `processo`, `dataInicio`, `dataFinal`, `horario`, `cargaHoraria`, `local`, `idAreaTematica`, `idCoordenador`, `idViceCoordenador`, `coordenadorArea`, `publicoAlvo`, `expectativaPublico`, `descricao`, `resumo`, `idRecursos`, `validado`, `modificadoEm`, `fechado`) VALUES
(1, 'ddd', NULL, '2008-08-30', '2008-10-25', 'ads', 123, 'asd', 1, 20, 15, 'teste', NULL, NULL, NULL, NULL, NULL, 0, '2008-08-31 21:10:04', 0),
(2, 'zxc', NULL, '2008-08-18', '2008-08-25', '123', 123, 'asd', 1, 20, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2008-08-31 21:12:08', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `curso_colaboradordocente`
--

CREATE TABLE IF NOT EXISTS `curso_colaboradordocente` (
  `idCurso` int(11) NOT NULL,
  `idColaboradorDocente` int(11) NOT NULL,
  PRIMARY KEY  (`idCurso`,`idColaboradorDocente`),
  KEY `idColaboradorDocente` (`idColaboradorDocente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `curso_colaboradordocente`
--


-- --------------------------------------------------------

--
-- Estrutura da tabela `departamento`
--

CREATE TABLE IF NOT EXISTS `departamento` (
  `id` int(11) NOT NULL auto_increment,
  `nome` varchar(255) NOT NULL default '',
  `idUnidade` int(11) NOT NULL,
  `login` char(20) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `idUnidade` (`idUnidade`),
  KEY `login` (`login`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 3072 kB; (`idUnidade`) REFER `proexc/unidade`(`' AUTO_INCREMENT=17 ;

--
-- Extraindo dados da tabela `departamento`
--

INSERT INTO `departamento` (`id`, `nome`, `idUnidade`, `login`) VALUES
(1, 'DCC', 1, 'admin'),
(2, 'DFIS', 1, 'admin'),
(7, 'Arquitetura', 2, 'admin'),
(8, 'Engenharia ElÃ©trica', 2, 'admin'),
(9, 'Engenharia Civil', 2, 'admin'),
(10, 'Dep Geografia', 5, 'admin'),
(13, 'Dep CiÃªncias BiolÃ³gicas', 4, 'admin'),
(16, 'EducaÃ§Ã£o FÃ­scia', 6, 'admin');

-- --------------------------------------------------------

--
-- Estrutura da tabela `evento`
--

CREATE TABLE IF NOT EXISTS `evento` (
  `id` int(11) NOT NULL auto_increment,
  `titulo` varchar(255) NOT NULL,
  `processo` char(13) default NULL,
  `especie` varchar(20) default NULL,
  `carater` enum('Local','Regional','Nacional','Internacional') default NULL,
  `dataInicio` date default NULL,
  `dataFinal` date default NULL,
  `horario` varchar(255) default NULL,
  `cargaHoraria` int(11) default NULL,
  `local` varchar(255) default NULL,
  `idAreaTematica` int(11) default NULL,
  `idCoordenador` int(11) default NULL,
  `publicoAlvo` text,
  `expectativaPublico` int(11) NOT NULL default '0',
  `docentesEnvolvidos` int(11) NOT NULL default '0',
  `bolsistasGraduacaoEnvolvidos` int(11) NOT NULL default '0',
  `bolsistasPosGraduacaoEnvolvidos` int(11) NOT NULL default '0',
  `voluntariosEnvolvidos` int(11) NOT NULL default '0',
  `tecnicosEnvolvidos` int(11) NOT NULL default '0',
  `comunidadeEnvolvida` int(11) NOT NULL default '0',
  `objetivos` text,
  `resumo` text,
  `idRecursos` int(11) default NULL,
  `validado` tinyint(1) NOT NULL default '0',
  `modificadoEm` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `fechado` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idAreaTematica` (`idAreaTematica`),
  KEY `idCoordenador` (`idCoordenador`),
  KEY `idRecursos` (`idRecursos`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Extraindo dados da tabela `evento`
--

INSERT INTO `evento` (`id`, `titulo`, `processo`, `especie`, `carater`, `dataInicio`, `dataFinal`, `horario`, `cargaHoraria`, `local`, `idAreaTematica`, `idCoordenador`, `publicoAlvo`, `expectativaPublico`, `docentesEnvolvidos`, `bolsistasGraduacaoEnvolvidos`, `bolsistasPosGraduacaoEnvolvidos`, `voluntariosEnvolvidos`, `tecnicosEnvolvidos`, `comunidadeEnvolvida`, `objetivos`, `resumo`, `idRecursos`, `validado`, `modificadoEm`, `fechado`) VALUES
(1, 'evento', '56465465', 'Outro', 'Nacional', '2008-04-25', '2008-05-02', 'das 08:00 Ã s 19:00', 200, 'ICE', 3, 19, 'sdfsdfsdfsd', 160, 5, 10, 0, 20, 5, 50, 'sdfsdfsd', 'sdfsdfsdf\r\nsdfsd\r\n', NULL, 0, '2008-08-31 20:46:01', 1),
(2, 'novo evento', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 19, NULL, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, 0, '2008-05-05 15:03:42', 0),
(3, 'asd', NULL, 'Congresso', 'Local', '2008-08-30', '2008-10-26', '08:00 Ã s 12:00', 200, 'UFJF', 1, 20, NULL, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, 0, '2008-08-31 20:58:17', 0),
(4, 'zxc', NULL, 'Congresso', 'Local', '2008-08-26', '2008-08-31', 'asdas', 123, 'asd', 1, 20, NULL, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, 0, '2008-08-31 20:58:53', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `linhaatuacao`
--

CREATE TABLE IF NOT EXISTS `linhaatuacao` (
  `id` int(11) NOT NULL auto_increment,
  `nome` varchar(255) NOT NULL default '',
  `login` char(20) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `login` (`login`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 3072 kB' AUTO_INCREMENT=3 ;

--
-- Extraindo dados da tabela `linhaatuacao`
--

INSERT INTO `linhaatuacao` (`id`, `nome`, `login`) VALUES
(1, 'Artes Cenicas', 'admin'),
(2, 'Artes GrÃ¡ficas', 'admin');

-- --------------------------------------------------------

--
-- Estrutura da tabela `parceiro`
--

CREATE TABLE IF NOT EXISTS `parceiro` (
  `id` int(11) NOT NULL auto_increment,
  `nomeInstituicao` varchar(255) NOT NULL default '',
  `cnpj` char(14) NOT NULL default '',
  `telefone` char(14) NOT NULL default '',
  `nomeResponsavel` varchar(255) NOT NULL default '',
  `nomeContato` varchar(255) NOT NULL default '',
  `telefoneContato` char(14) NOT NULL default '',
  `celularContato` char(14) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 3072 kB' AUTO_INCREMENT=12 ;

--
-- Extraindo dados da tabela `parceiro`
--

INSERT INTO `parceiro` (`id`, `nomeInstituicao`, `cnpj`, `telefone`, `nomeResponsavel`, `nomeContato`, `telefoneContato`, `celularContato`) VALUES
(1, 'aaaa', '', '', '', '', '', NULL),
(2, 'parceiro', '455465', '(32)3232-3232', 'responsavel', 'contato', '(21)6655-5545', NULL),
(3, 'parceiro dois', '5465465465', '(32)5465-5646', 'Responsavel', 'contato', '(54)5546-5646', NULL),
(4, 'InstituiÃ§Ã£o', '123123213', '(34)3232-3232', 'Responsavel', 'Contato', '(32)3232-3232', NULL),
(5, 'instituicao', '5465456432', '(32)3232-5454', 'responsavel', 'contato', '(32)3232-3232', NULL),
(6, 'instituicao dois', '54564', '(32)3232-3232', 'responsavel', 'contato', '(32)5654-5465', NULL),
(7, 'asdasd', '654654', '(32)3232-3232', 'asdasdas', 'sadasd', '(32)3232-3232', NULL),
(8, 'bcvbcvbcv', '32423432', '(32)3232-3232', 'sdadasdsa', 'sdadasdas', '(32)3232-0323', NULL),
(9, 'sdafasdf', '12313123', '(32)3232-3232', 'asdasdas', 'dsfsdafsf', '(32)3232-3232', NULL),
(10, 'qweqweqeq', '46546546', '(32)3232-3232', 'weqeqwe', 'wqeqwe', '(32)3232-3232', ''),
(11, 'jkhjkhjkhj', '654654', '(32)3232-0323', 'lkjlkjklj', 'lkjkljklj', '(32)3232-3232', '');

-- --------------------------------------------------------

--
-- Estrutura da tabela `programa`
--

CREATE TABLE IF NOT EXISTS `programa` (
  `id` int(11) NOT NULL auto_increment,
  `nome` varchar(255) NOT NULL,
  `login` char(20) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `login` (`login`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 10240 kB' AUTO_INCREMENT=3 ;

--
-- Extraindo dados da tabela `programa`
--

INSERT INTO `programa` (`id`, `nome`, `login`) VALUES
(1, 'Boa VizinhanÃ§a', 'admin'),
(2, 'AtenÃ§Ã£o Ã  SaÃºde Bucal', 'admin');

-- --------------------------------------------------------

--
-- Estrutura da tabela `projeto`
--

CREATE TABLE IF NOT EXISTS `projeto` (
  `id` int(11) NOT NULL auto_increment,
  `titulo` varchar(255) NOT NULL default '',
  `processo` char(13) default NULL,
  `idPrograma` int(11) default NULL,
  `idCoordenador` int(11) NOT NULL,
  `idViceCoordenador` int(11) default NULL,
  `idAreaTematica` int(11) default NULL,
  `idLinhaAtuacao` int(11) default NULL,
  `fundamento` text,
  `objetivos` text,
  `metodologia` text,
  `publicoAlvo` text,
  `pessoasAtendidas` text,
  `resumo` text,
  `bolsasJustificativa` text,
  `continuo` tinyint(1) default NULL,
  `dataInicio` date default NULL,
  `dataFinal` date default NULL,
  `bolsasPretendidas` int(11) NOT NULL default '0',
  `bolsasConcedidas` int(11) default NULL,
  `idRecursos` int(11) default NULL,
  `modificadoEm` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `fechado` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idAreaTematica` (`idAreaTematica`),
  KEY `idCoordenador` (`idCoordenador`),
  KEY `idLinhaAtuacao` (`idLinhaAtuacao`),
  KEY `idPrograma` (`idPrograma`),
  KEY `idRecursos` (`idRecursos`),
  KEY `idViceCoordenador` (`idViceCoordenador`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 10240 kB; (`idAreaTematica`) REFER `proexc/area' AUTO_INCREMENT=44 ;

--
-- Extraindo dados da tabela `projeto`
--

INSERT INTO `projeto` (`id`, `titulo`, `processo`, `idPrograma`, `idCoordenador`, `idViceCoordenador`, `idAreaTematica`, `idLinhaAtuacao`, `fundamento`, `objetivos`, `metodologia`, `publicoAlvo`, `pessoasAtendidas`, `resumo`, `bolsasJustificativa`, `continuo`, `dataInicio`, `dataFinal`, `bolsasPretendidas`, `bolsasConcedidas`, `idRecursos`, `modificadoEm`, `fechado`) VALUES
(3, 'teste', NULL, NULL, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2008-04-17 16:43:06', 0),
(4, 'teste2', NULL, NULL, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2008-04-17 16:43:11', 0),
(5, 'teste', NULL, NULL, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2008-04-17 16:43:14', 0),
(6, 'teste2', NULL, NULL, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2008-04-17 16:43:17', 0),
(19, 'bbbbbb', '546546', 2, 17, 15, 1, 1, '\r\n', '\r\n', '\r\n', '\r\n', '\r\n', '\r\n', 'asdasdas\r\n', 0, '2008-01-29', '2008-02-29', 2, 2, NULL, '2008-08-31 16:10:12', 1),
(20, 'aaaa', '6546546', 2, 17, 15, 1, 1, 'teste\r\n\r\náéíóú\r\nàèìòù\r\nãõ\r\nâêîôû \r\nÁÉÍÓÚ\r\nÀÈÌÒÙ\r\nÃÕ\r\nÂÊÎÔÛ', '', '    	    ', '    	    ', '', '', '', 0, '2008-02-01', '2009-02-01', 0, 2, NULL, '2008-08-31 16:10:12', 1),
(21, 'teste', '6546546', 2, 17, 15, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2008-02-22', '2008-04-26', 0, 2, NULL, '2008-08-31 16:10:12', 1),
(22, 'teste', '85189518', 1, 17, 15, 5, 2, 'Ã£sdasdÃ¡sdas sÂ´da dd', 'dasdas sadasd', 'sadasdas', 'dasd\r\nasdasd\r\nasdasda\r\n', 'sadasd\r\nsadas\r\n', 'sadasdas\r\n\r\n', 'asdasdasd', 0, '2008-03-10', '2009-03-09', 2, 1, 2, '2008-08-31 16:10:12', 1),
(23, 'projeto', NULL, NULL, 17, 15, 1, 1, '\r\n', '\r\n', '\r\n', '\r\n', '\r\n', '\r\n', '\r\n', 1, '2008-03-13', NULL, 0, NULL, 3, '2008-04-17 16:44:04', 0),
(25, 'oooooooooo', NULL, NULL, 17, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2008-03-14', '2009-03-13', 0, NULL, 4, '2008-04-17 16:44:07', 0),
(26, 'adrasf', NULL, NULL, 17, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2008-04-17 16:44:10', 0),
(27, 'adsf', NULL, NULL, 17, 15, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2008-03-26', NULL, 0, NULL, NULL, '2008-04-17 16:44:13', 0),
(28, 'reerereee', '65765765', 1, 17, NULL, 3, 1, 'asdassadasd\r\nasdasdadasd\r\nadadasdasdasd\r\nasdasdadsad\r\n', 'asdasdasdasda\r\ndasdasdasdas\r\n', '\r\n', '\r\n', '\r\n', 'sasdas sdasdasj ahsjkdhaskjh asdjhakjdhasjhd\r\nasdasd sadasdas dsadadas\r\n', 'asdasdasd\r\ndasdasdasd\r\n', 1, '2008-03-25', NULL, 3, 2, 6, '2008-08-31 16:10:12', 1),
(29, 'asd', NULL, NULL, 17, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2008-04-17 16:44:19', 0),
(30, 'CiÃªncia Viva', NULL, NULL, 17, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2008-04-17 16:44:22', 0),
(32, 'sdadasd', NULL, NULL, 17, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2008-04-08', '2009-04-22', 0, NULL, NULL, '2008-04-17 16:44:24', 0),
(33, 'titulo', NULL, NULL, 18, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2008-04-17 16:44:28', 0),
(35, 'Projeto dois', '1123554', 1, 19, 15, 8, 2, 'aaaaaaaaaa aaaaaa aaa a aaaaaa aaaa a aaaaaaaaa aaaaaaaa\r\naaaaaaaaaa aaaaaa aaa a aaaaaa aaaa a aaaaaaaaa aaaaaaaa\r\naaaaaaaaaa aaaaaa aaa a aaaaaa aaaa a aaaaaaaaa aaaaaaaa\r\naaaaaaaaaa aaaaaa aaa a aaaaaa aaaa a aaaaaaaaa aaaaaaaa\r\naaaaaaaaaa aaaaaa aaa a aaaaaa aaaa a aaaaaaaaa aaaaaaaa\r\naaaaaaaaaa aaaaaa aaa a aaaaaa aaaa a aaaaaaaaa aaaaaaaa\r\n\r\n', 'aaaaaaaaaa aaaaaa aaa a aaaaaa aaaa a aaaaaaaaa aaaaaaaaaaaaaaaaaa aaaaaa aaa a aaaaaa aaaa a aaaaaaaaa aaaaaaaaaaaaaaaaaa aaaaaa aaa a aaaaaa aaaa a aaaaaaaaa aaaaaaaaaaaaaaaaaa aaaaaa aaa a aaaaaa aaaa a aaaaaaaaa aaaaaaaaaaaaaaaaaa aaaaaa aaa a aaaaaa aaaa a aaaaaaaaa aaaaaaaaaaaaaaaaaa aaaaaa aaa a aaaaaa aaaa a aaaaaaaaa aaaaaaaaaaaaaaaaaa aaaaaa aaa a aaaaaa aaaa a aaaaaaaaa aaaaaaaaaaaaaaaaaa aaaaaa aaa a aaaaaa aaaa a aaaaaaaaa aaaaaaaa\r\n', 'aaaaaaaaaa aaaaaa aaa a aaaaaa aaaa a aaaaaaaaa aaaaaaaaaaaaaaaaaa aaaaaa aaa a aaaaaa aaaa a aaaaaaaaa aaaaaaaaaaaaaaaaaa aaaaaa aaa a aaaaaa aaaa a aaaaaaaaa aaaaaaaaaaaaaaaaaa aaaaaa aaa a aaaaaa aaaa a aaaaaaaaa aaaaaaaaaaaaaaaaaa aaaaaa aaa a aaaaaa aaaa a aaaaaaaaa aaaaaaaaaaaaaaaaaa aaaaaa aaa a aaaaaa aaaa a aaaaaaaaa aaaaaaaaaaaaaaaaaa aaaaaa aaa a aaaaaa aaaa a aaaaaaaaa aaaaaaaa\r\n', 'aaaaaaaaaa aaaaaa aaa a aaaaaa aaaa a aaaaaaaaa aaaaaaaaaaaaaaaaaa aaaaaa aaa a aaaaaa aaaa a aaaaaaaaa aaaaaaaaaaaaaaaaaa aaaaaa aaa a aaaaaa aaaa a aaaaaaaaa aaaaaaaaaaaaaaaaaa aaaaaa aaa a aaaaaa aaaa a aaaaaaaaa aaaaaaaaaaaaaaaaaa aaaaaa aaa a aaaaaa aaaa a aaaaaaaaa aaaaaaaaaaaaaaaaaa aaaaaa aaa a aaaaaa aaaa a aaaaaaaaa aaaaaaaaaaaaaaaaaa aaaaaa aaa a aaaaaa aaaa a aaaaaaaaa aaaaaaaa\r\n', 'aaaaaaaaaa aaaaaa aaa a aaaaaa aaaa a aaaaaaaaa aaaaaaaaaaaaaaaaaa aaaaaa aaa a aaaaaa aaaa a aaaaaaaaa aaaaaaaaaaaaaaaaaa aaaaaa aaa a aaaaaa aaaa a aaaaaaaaa aaaaaaaaaaaaaaaaaa aaaaaa aaa a aaaaaa aaaa a aaaaaaaaa aaaaaaaaaaaaaaaaaa aaaaaa aaa a aaaaaa aaaa a aaaaaaaaa aaaaaaaaaaaaaaaaaa aaaaaa aaa a aaaaaa aaaa a aaaaaaaaa aaaaaaaa\r\n', 'aaaaaaaaaa aaaaaa aaa a aaaaaa aaaa a aaaaaaaaa aaaaaaaaaaaaaaaaaa aaaaaa aaa a aaaaaa aaaa a aaaaaaaaa aaaaaaaaaaaaaaaaaa aaaaaa aaa a aaaaaa aaaa a aaaaaaaaa aaaaaaaaaaaaaaaaaa aaaaaa aaa a aaaaaa aaaa a aaaaaaaaa aaaaaaaaaaaaaaaaaa aaaaaa aaa a aaaaaa aaaa a aaaaaaaaa aaaaaaaaaaaaaaaaaa aaaaaa aaa a aaaaaa aaaa a aaaaaaaaa aaaaaaaa\r\n', 'aaaaaaaaaa aaaaaa aaa a aaaaaa aaaa a aaaaaaaaa aaaaaaaaaaaaaaaaaa aaaaaa aaa a aaaaaa aaaa a aaaaaaaaa aaaaaaaaaaaaaaaaaa aaaaaa aaa a aaaaaa aaaa a aaaaaaaaa aaaaaaaaaaaaaaaaaa aaaaaa aaa a aaaaaa aaaa a aaaaaaaaa aaaaaaaaaaaaaaaaaa aaaaaa aaa a aaaaaa aaaa a aaaaaaaaa aaaaaaaaaaaaaaaaaa aaaaaa aaa a aaaaaa aaaa a aaaaaaaaa aaaaaaaaaaaaaaaaaa aaaaaa aaa a aaaaaa aaaa a aaaaaaaaa aaaaaaaa\r\n', 1, '2008-04-18', NULL, 10, 5, 7, '2008-08-31 16:10:12', 1),
(36, 'teste', '23423432', 1, 19, NULL, 9, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2008-04-17', '2009-04-15', 0, 0, NULL, '2008-08-31 16:10:12', 1),
(37, 'Projeto Vazio', NULL, NULL, 19, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2008-04-17 16:44:38', 0),
(39, 'nnn', NULL, NULL, 19, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2008-04-22', '2009-04-22', 0, NULL, NULL, '2008-04-29 14:53:25', 0),
(40, 'dsadsa', '4444', 1, 20, 15, 1, 1, 'aaaaaaaaaa\r\naaaaaaaaaaaa\r\n', 'aaaaaaaaaaaaaaaa\r\n\r\n', 'aaaaaaaaaaaa', 'aaaaaaaaaaa', 'aaaaaaaaaaaa', 'aaaaaaaaaaa', 'aaaaaaaaaaa\r\naaaaaaaaaaa\r\n\r\nbbbbbbbbb\r\n', 0, '2008-07-29', '2009-07-22', 2, 1, NULL, '2008-08-31 16:10:12', 1),
(41, 'kjhjh', NULL, NULL, 20, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2008-08-31', NULL, 0, NULL, NULL, '2008-08-31 18:20:52', 1),
(42, 'dfg', NULL, NULL, 20, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2008-08-30', '2009-08-27', 0, NULL, NULL, '2008-09-01 14:28:37', 1),
(43, 'dgfgd', NULL, NULL, 20, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2008-09-22', NULL, 0, NULL, NULL, '2008-09-01 14:26:17', 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `projeto_colaboradordocente`
--

CREATE TABLE IF NOT EXISTS `projeto_colaboradordocente` (
  `idProjeto` int(11) NOT NULL,
  `idColaboradorDocente` int(11) NOT NULL,
  PRIMARY KEY  (`idProjeto`,`idColaboradorDocente`),
  KEY `idColaboradorDocente` (`idColaboradorDocente`),
  KEY `idProjeto` (`idProjeto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 3072 kB; (`idColaboradorDocente`) REFER `proexc';

--
-- Extraindo dados da tabela `projeto_colaboradordocente`
--

INSERT INTO `projeto_colaboradordocente` (`idProjeto`, `idColaboradorDocente`) VALUES
(19, 6),
(19, 7),
(19, 8),
(22, 9),
(35, 11),
(35, 12);

-- --------------------------------------------------------

--
-- Estrutura da tabela `projeto_colaboradorexterno`
--

CREATE TABLE IF NOT EXISTS `projeto_colaboradorexterno` (
  `idProjeto` int(11) NOT NULL,
  `idColaboradorExterno` int(11) NOT NULL,
  PRIMARY KEY  (`idProjeto`,`idColaboradorExterno`),
  KEY `idColaboradorExterno` (`idColaboradorExterno`),
  KEY `idProjeto` (`idProjeto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 3072 kB; (`idColaboradorExterno`) REFER `proexc';

--
-- Extraindo dados da tabela `projeto_colaboradorexterno`
--

INSERT INTO `projeto_colaboradorexterno` (`idProjeto`, `idColaboradorExterno`) VALUES
(19, 2),
(19, 3),
(22, 4),
(35, 5),
(35, 6);

-- --------------------------------------------------------

--
-- Estrutura da tabela `projeto_parceiro`
--

CREATE TABLE IF NOT EXISTS `projeto_parceiro` (
  `idProjeto` int(11) NOT NULL,
  `idParceiro` int(11) NOT NULL,
  PRIMARY KEY  (`idProjeto`,`idParceiro`),
  KEY `idParceiro` (`idParceiro`),
  KEY `idProjeto` (`idProjeto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 3072 kB; (`idParceiro`) REFER `proexc/parceiro`';

--
-- Extraindo dados da tabela `projeto_parceiro`
--

INSERT INTO `projeto_parceiro` (`idProjeto`, `idParceiro`) VALUES
(19, 2),
(19, 3),
(22, 4),
(23, 5),
(23, 6),
(28, 9),
(35, 10),
(35, 11);

-- --------------------------------------------------------

--
-- Estrutura da tabela `projeto_tecnico`
--

CREATE TABLE IF NOT EXISTS `projeto_tecnico` (
  `idProjeto` int(11) NOT NULL,
  `idTecnico` int(11) NOT NULL,
  `funcao` char(11) NOT NULL default '',
  PRIMARY KEY  (`idProjeto`,`idTecnico`),
  KEY `idProjeto` (`idProjeto`),
  KEY `idTecnico` (`idTecnico`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 3072 kB; (`idProjeto`) REFER `proexc/projeto`(`';

--
-- Extraindo dados da tabela `projeto_tecnico`
--

INSERT INTO `projeto_tecnico` (`idProjeto`, `idTecnico`, `funcao`) VALUES
(19, 31, 'coordenador'),
(19, 32, 'coordenador'),
(19, 33, 'colaborador'),
(19, 34, 'colaborador'),
(19, 35, 'coordenador'),
(21, 36, 'colaborador'),
(22, 37, 'coordenador'),
(22, 38, 'colaborador'),
(28, 40, 'coordenador'),
(35, 43, 'coordenador'),
(35, 44, 'coordenador'),
(35, 45, 'colaborador'),
(35, 46, 'colaborador');

-- --------------------------------------------------------

--
-- Estrutura da tabela `recursos`
--

CREATE TABLE IF NOT EXISTS `recursos` (
  `id` int(11) NOT NULL auto_increment,
  `gestora` varchar(255) NOT NULL default '',
  `ano` int(11) NOT NULL,
  `recursosUfjfFonte` varchar(255) default NULL,
  `recursosUfjfValor` double default NULL,
  `recursosExternosFonte` varchar(255) default NULL,
  `recursosExternosValor` double default NULL,
  `diariaUfjf` double default NULL,
  `diariaExterno` double default NULL,
  `passagemUfjf` double default NULL,
  `passagemExterno` double default NULL,
  `alimentacaoUfjf` double default NULL,
  `alimentacaoExterno` double default NULL,
  `bolsaDiscente` double default NULL,
  `pagamentoCoordenador` double default NULL,
  `pagamentoEquipe` double default NULL,
  `pagamentoPJUfjf` double default NULL,
  `pagamentoPJExterno` double default NULL,
  `pagamentoPFUfjf` double default NULL,
  `pagamentoPFExterno` double default NULL,
  `equipamentos` double default NULL,
  `material` double default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 3072 kB' AUTO_INCREMENT=8 ;

--
-- Extraindo dados da tabela `recursos`
--

INSERT INTO `recursos` (`id`, `gestora`, `ano`, `recursosUfjfFonte`, `recursosUfjfValor`, `recursosExternosFonte`, `recursosExternosValor`, `diariaUfjf`, `diariaExterno`, `passagemUfjf`, `passagemExterno`, `alimentacaoUfjf`, `alimentacaoExterno`, `bolsaDiscente`, `pagamentoCoordenador`, `pagamentoEquipe`, `pagamentoPJUfjf`, `pagamentoPJExterno`, `pagamentoPFUfjf`, `pagamentoPFExterno`, `equipamentos`, `material`) VALUES
(1, '', 0, NULL, NULL, '', 0, NULL, 0, NULL, 0, NULL, 0, 0, 0, 0, NULL, 0, NULL, 0, 0, 0),
(2, '', 0, NULL, NULL, '', 10000, NULL, 100, NULL, 100, NULL, 1000, 1000, 0, 5000, NULL, 1000, NULL, 0, 0, 0),
(3, 'fundacao', 2008, NULL, NULL, 'fonte', 10000, NULL, 5000, NULL, 200, NULL, 200, 100, 400, 100, NULL, 100, NULL, 300, 2100, 1100),
(4, 'fundacao', 2008, NULL, NULL, 'fonte', 10000, NULL, 1000, NULL, 1000, NULL, 1000, 500, 1000, 500, NULL, 1000, NULL, 500, 1000, 1000),
(6, 'asdasdas', 2008, NULL, NULL, 'asdasd', 10000, NULL, 1000, NULL, 1000, NULL, 1000, 1000, 1000, 1000, NULL, 1000, NULL, 1000, 500, 0),
(7, 'gestora', 2008, NULL, NULL, 'fonte', 10000, NULL, 1000, NULL, 1000, NULL, 1000, 1000, 1000, 1000, NULL, 1000, NULL, 1000, 500, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `relatoriofinal`
--

CREATE TABLE IF NOT EXISTS `relatoriofinal` (
  `idRelatorioFinal` int(11) NOT NULL auto_increment,
  `disciplinas` varchar(255) NOT NULL,
  `estagio` varchar(255) NOT NULL,
  `creditos` varchar(255) NOT NULL,
  `projeto` varchar(255) NOT NULL,
  `docentesEnvolvidos` int(11) NOT NULL,
  `alunosGraduacaoBolsistasEnvolvidos` int(11) NOT NULL,
  `alunosGraduacaoNaoBolsistasEnvolvidos` int(11) NOT NULL,
  `alunosPosGraduacaoEnvolvidos` int(11) NOT NULL,
  `tecnicosAdministrativosEnvolvidos` int(11) NOT NULL,
  `pessoasOutrasIESEnvolvidas` int(11) NOT NULL,
  `pessoasComunidadeEnvolvidas` int(11) NOT NULL,
  `publicoAtingido` int(11) NOT NULL,
  `atendimentosSemanaisGrupo` int(11) NOT NULL,
  `atendimentosSemanaisIndividuais` int(11) NOT NULL,
  `producaoLivros` int(11) NOT NULL,
  `producaoComunicacao` int(11) NOT NULL,
  `producaoProgramasRadio` int(11) NOT NULL,
  `producaoCapitulosLivros` int(11) NOT NULL,
  `producaoRelatoriosTecnicos` int(11) NOT NULL,
  `producaoProgramasTV` int(11) NOT NULL,
  `producaoAnais` int(11) NOT NULL,
  `producaoAudiovisualFilme` int(11) NOT NULL,
  `producaoAplicativosComputador` int(11) NOT NULL,
  `producaoManuais` int(11) NOT NULL,
  `producaoAudiovisualVideos` int(11) NOT NULL,
  `producaoJogosEducativos` int(11) NOT NULL,
  `producaoJornais` int(11) NOT NULL,
  `producaoAudiovisualCDs` int(11) NOT NULL,
  `producaoProdutosArtisticos` int(11) NOT NULL,
  `producaoRevistas` int(11) NOT NULL,
  `producaoAudiovisualDVDs` int(11) NOT NULL,
  `producaoArtigos` int(11) NOT NULL,
  `producaoAudiovisualOutros` int(11) NOT NULL,
  `producaoOutros` int(11) NOT NULL,
  `producaoOutrosTexto` varchar(255) NOT NULL,
  `producaoDetalhamento` text NOT NULL,
  `relatorioFinal` text NOT NULL,
  PRIMARY KEY  (`idRelatorioFinal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Extraindo dados da tabela `relatoriofinal`
--


-- --------------------------------------------------------

--
-- Estrutura da tabela `tecnico`
--

CREATE TABLE IF NOT EXISTS `tecnico` (
  `id` int(11) NOT NULL auto_increment,
  `siape` char(7) NOT NULL,
  `nome` varchar(255) NOT NULL default '',
  `idDepartamento` int(11) NOT NULL,
  `email` varchar(255) NOT NULL default '',
  `telefone` char(14) NOT NULL default '',
  `telefonePublico` char(14) NOT NULL default '',
  `celular` char(14) default NULL,
  `cargaHorariaSemanal` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `idDepartamento` (`idDepartamento`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 3072 kB; (`idDepartamento`) REFER `proexc/depar' AUTO_INCREMENT=47 ;

--
-- Extraindo dados da tabela `tecnico`
--

INSERT INTO `tecnico` (`id`, `siape`, `nome`, `idDepartamento`, `email`, `telefone`, `telefonePublico`, `celular`, `cargaHorariaSemanal`) VALUES
(20, '', 'aaaa', 1, '', '', '', '', NULL),
(23, '', 'bbb', 1, '', '', '', '', NULL),
(27, '', 'cccc', 1, '', '', '', '', NULL),
(28, '', 'asdas', 1, 'asdas@dasd.com', '(32)3232-3232', '(32)3232-2121', '', NULL),
(29, '', 'fsadsf', 2, 'sadsa@dasds.com', '(32)2323-2323', '(32)2323-3232', '', NULL),
(30, '', 'gfdgfd', 1, 'khhkjh@hfhgf.com', '(76)6876-9787', '(76)7977-9787', '', NULL),
(31, '', 'coordenador tecnico um', 1, 'asdas@dsadas.com', '(22)2222-2222', '(22)2222-2222', '', NULL),
(32, '', 'coordenador tecnico dois', 1, 'asdasd@dsada.com', '(32)3232-3232', '(32)3232-3232', '', NULL),
(33, '', 'colaborador tecnico um', 1, 'sadasd@dsadas.com', '(32)3232-3232', '(22)3232-3232', '', NULL),
(34, '', 'colaborador tecnico dois', 1, 'dasda@sdasd.com', '(32)3232-3232', '(32)3232-3232', '', NULL),
(35, '', 'Coordenador tecnico', 1, 'sdas@dsad.com', '(42)5465-5466', '(32)5454-5656', '', NULL),
(36, '', 'tÃ©cnico malandro', 1, 'adasd@dsad.com', '(32)3232-3232', '(32)3232-3232', '', NULL),
(37, '', 'coordenador tecnico', 1, 'sdad@dsada.com', '(23)3232-3232', '(32)3232-3232', '', NULL),
(38, '', 'fsfsdsdf', 1, 'sdfs@dsada.com', '(32)3232-3232', '(32)3232-3232', '', NULL),
(39, '', 'sdfaasda', 1, 'ssadas@dasd.com', '(32)3232-3232', '(32)3232-3232', '', NULL),
(40, '', 'sdfsfsd', 1, 'asdsad@dasdas.com', '(32)3232-3232', '(32)3232-3232', '', NULL),
(41, '', 'TecnicÃ£o Malandro', 1, 'sdasda@dasdas.com', '(32)3232-3232', '(32)3232-3232', '(32)8822-1174', NULL),
(42, '', 'a', 1, 'a@aa.com', '(32)3232-3232', '', '', 1),
(43, '', 'asasdas', 1, 'asda@sads.com', '(43)4343-4343', '(43)4334-4343', '(43)4343-4343', 10),
(44, '', 'sdasdasd', 10, 'asdasd@dasda.com', '(32)3232-3232', '(32)3232-3232', '(32)3232-3232', 10),
(45, '', 'asdasdaq', 1, 'sadasd@dasda.com', '(32)3232-3232', '(32)3232-3232', '(32)3232-3232', 10),
(46, '', 'xzczxcz', 1, 'xczczx@cxzc.com', '(32)3232-3232', '(32)3232-0323', '(32)3232-3232', 10);

-- --------------------------------------------------------

--
-- Estrutura da tabela `titulacao`
--

CREATE TABLE IF NOT EXISTS `titulacao` (
  `id` int(11) NOT NULL auto_increment,
  `nome` char(20) NOT NULL default '',
  `login` char(20) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `login` (`login`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 3072 kB' AUTO_INCREMENT=6 ;

--
-- Extraindo dados da tabela `titulacao`
--

INSERT INTO `titulacao` (`id`, `nome`, `login`) VALUES
(1, 'Mestre', 'admin'),
(2, 'Doutor', 'admin'),
(3, 'PÃ³s Doutor', 'admin'),
(4, 'Graduado', 'admin'),
(5, 'Especialista', 'admin');

-- --------------------------------------------------------

--
-- Estrutura da tabela `unidade`
--

CREATE TABLE IF NOT EXISTS `unidade` (
  `id` int(11) NOT NULL auto_increment,
  `nome` varchar(255) NOT NULL default '',
  `login` char(20) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `login` (`login`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 3072 kB' AUTO_INCREMENT=7 ;

--
-- Extraindo dados da tabela `unidade`
--

INSERT INTO `unidade` (`id`, `nome`, `login`) VALUES
(1, 'ICE', 'admin'),
(2, 'Faculdade de Engenharia', 'admin'),
(4, 'ICB', 'admin'),
(5, 'ICH', 'admin'),
(6, 'FAEFID', 'admin');

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
  `nome` varchar(255) NOT NULL,
  `login` char(20) NOT NULL default '',
  `role` varchar(15) NOT NULL default 'guest',
  `senha` varchar(255) default NULL,
  PRIMARY KEY  (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `usuario`
--

INSERT INTO `usuario` (`nome`, `login`, `role`, `senha`) VALUES
('Administrador', 'admin', 'admin', '21232f297a57a5a743894a0e4a801fc3'),
('Manager da Silva', 'manager', 'manager', '202cb962ac59075b964b07152d234b70'),
('teste', 'teste', 'admin', '202cb962ac59075b964b07152d234b70');

--
-- Restrições para as tabelas dumpadas
--

--
-- Restrições para a tabela `agenda`
--
ALTER TABLE `agenda`
  ADD CONSTRAINT `agenda_ibfk_1` FOREIGN KEY (`login`) REFERENCES `usuario` (`login`) ON UPDATE CASCADE;

--
-- Restrições para a tabela `areatematica`
--
ALTER TABLE `areatematica`
  ADD CONSTRAINT `areatematica_ibfk_1` FOREIGN KEY (`login`) REFERENCES `usuario` (`login`) ON UPDATE CASCADE;

--
-- Restrições para a tabela `colaboradordocente`
--
ALTER TABLE `colaboradordocente`
  ADD CONSTRAINT `FK_ColaboradorDocente_Departamento` FOREIGN KEY (`idDepartamento`) REFERENCES `departamento` (`id`);

--
-- Restrições para a tabela `convenio`
--
ALTER TABLE `convenio`
  ADD CONSTRAINT `convenio_ibfk_1` FOREIGN KEY (`login`) REFERENCES `usuario` (`login`) ON UPDATE CASCADE;

--
-- Restrições para a tabela `coordenador`
--
ALTER TABLE `coordenador`
  ADD CONSTRAINT `FK_Professor_Departamento` FOREIGN KEY (`idDepartamento`) REFERENCES `departamento` (`id`),
  ADD CONSTRAINT `FK_Professor_Titulacao` FOREIGN KEY (`idTitulacao`) REFERENCES `titulacao` (`id`);

--
-- Restrições para a tabela `curso`
--
ALTER TABLE `curso`
  ADD CONSTRAINT `curso_fk2` FOREIGN KEY (`idRecursos`) REFERENCES `recursos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `curso_ibfk_5` FOREIGN KEY (`idAreaTematica`) REFERENCES `areatematica` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `curso_ibfk_6` FOREIGN KEY (`idCoordenador`) REFERENCES `coordenador` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `curso_ibfk_7` FOREIGN KEY (`idViceCoordenador`) REFERENCES `coordenador` (`id`) ON UPDATE CASCADE;

--
-- Restrições para a tabela `curso_colaboradordocente`
--
ALTER TABLE `curso_colaboradordocente`
  ADD CONSTRAINT `curso_colaboradordocente_ibfk_3` FOREIGN KEY (`idCurso`) REFERENCES `curso` (`id`),
  ADD CONSTRAINT `curso_colaboradordocente_ibfk_4` FOREIGN KEY (`idColaboradorDocente`) REFERENCES `colaboradordocente` (`id`);

--
-- Restrições para a tabela `departamento`
--
ALTER TABLE `departamento`
  ADD CONSTRAINT `FK_Departamento_Unidade` FOREIGN KEY (`idUnidade`) REFERENCES `unidade` (`id`);

--
-- Restrições para a tabela `evento`
--
ALTER TABLE `evento`
  ADD CONSTRAINT `evento_fk` FOREIGN KEY (`idAreaTematica`) REFERENCES `areatematica` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `evento_fk1` FOREIGN KEY (`idCoordenador`) REFERENCES `coordenador` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `evento_fk2` FOREIGN KEY (`idRecursos`) REFERENCES `recursos` (`id`) ON UPDATE CASCADE;

--
-- Restrições para a tabela `linhaatuacao`
--
ALTER TABLE `linhaatuacao`
  ADD CONSTRAINT `linhaatuacao_ibfk_1` FOREIGN KEY (`login`) REFERENCES `usuario` (`login`) ON UPDATE CASCADE;

--
-- Restrições para a tabela `programa`
--
ALTER TABLE `programa`
  ADD CONSTRAINT `programa_ibfk_1` FOREIGN KEY (`login`) REFERENCES `usuario` (`login`) ON UPDATE CASCADE;

--
-- Restrições para a tabela `projeto`
--
ALTER TABLE `projeto`
  ADD CONSTRAINT `FK_Projeto_AreaTematica` FOREIGN KEY (`idAreaTematica`) REFERENCES `areatematica` (`id`),
  ADD CONSTRAINT `FK_Projeto_Coordenador` FOREIGN KEY (`idCoordenador`) REFERENCES `coordenador` (`id`),
  ADD CONSTRAINT `FK_Projeto_LinhaAtuacao` FOREIGN KEY (`idLinhaAtuacao`) REFERENCES `linhaatuacao` (`id`),
  ADD CONSTRAINT `FK_Projeto_Programa` FOREIGN KEY (`idPrograma`) REFERENCES `programa` (`id`),
  ADD CONSTRAINT `FK_Projeto_Recursos` FOREIGN KEY (`idRecursos`) REFERENCES `recursos` (`id`),
  ADD CONSTRAINT `FK_Projeto_ViceCoordenador` FOREIGN KEY (`idViceCoordenador`) REFERENCES `coordenador` (`id`);

--
-- Restrições para a tabela `projeto_colaboradordocente`
--
ALTER TABLE `projeto_colaboradordocente`
  ADD CONSTRAINT `FK_Projeto_ColaboradorDocente_ColaboradorDocente` FOREIGN KEY (`idColaboradorDocente`) REFERENCES `colaboradordocente` (`id`),
  ADD CONSTRAINT `FK_Projeto_ColaboradorDocente_Projeto` FOREIGN KEY (`idProjeto`) REFERENCES `projeto` (`id`);

--
-- Restrições para a tabela `projeto_colaboradorexterno`
--
ALTER TABLE `projeto_colaboradorexterno`
  ADD CONSTRAINT `FK_Projeto_ColaboradorExterno_ColaboradorExterno` FOREIGN KEY (`idColaboradorExterno`) REFERENCES `colaboradorexterno` (`id`),
  ADD CONSTRAINT `FK_Projeto_ColaboradorExterno_Projeto` FOREIGN KEY (`idProjeto`) REFERENCES `projeto` (`id`);

--
-- Restrições para a tabela `projeto_parceiro`
--
ALTER TABLE `projeto_parceiro`
  ADD CONSTRAINT `FK_Projeto_Parceiro_Parceiro` FOREIGN KEY (`idParceiro`) REFERENCES `parceiro` (`id`),
  ADD CONSTRAINT `FK_Projeto_Parceiro_Projeto` FOREIGN KEY (`idProjeto`) REFERENCES `projeto` (`id`);

--
-- Restrições para a tabela `projeto_tecnico`
--
ALTER TABLE `projeto_tecnico`
  ADD CONSTRAINT `FK_Projeto_Tecnico_Projeto` FOREIGN KEY (`idProjeto`) REFERENCES `projeto` (`id`),
  ADD CONSTRAINT `FK_Projeto_Tecnico_Tecnico` FOREIGN KEY (`idTecnico`) REFERENCES `tecnico` (`id`);

--
-- Restrições para a tabela `tecnico`
--
ALTER TABLE `tecnico`
  ADD CONSTRAINT `FK_Tecnico_Departamento` FOREIGN KEY (`idDepartamento`) REFERENCES `departamento` (`id`);

--
-- Restrições para a tabela `titulacao`
--
ALTER TABLE `titulacao`
  ADD CONSTRAINT `titulacao_ibfk_1` FOREIGN KEY (`login`) REFERENCES `usuario` (`login`) ON UPDATE CASCADE;

--
-- Restrições para a tabela `unidade`
--
ALTER TABLE `unidade`
  ADD CONSTRAINT `unidade_ibfk_1` FOREIGN KEY (`login`) REFERENCES `usuario` (`login`) ON UPDATE CASCADE;
