drop table if exists comments;
drop table if exists posts;

create table posts (
  id int(11) NOT NULL AUTO_INCREMENT,
  title varchar(255) NOT NULL,
  body text NOT NULL,
  date_created datetime NOT NULL,
  last_updated datetime NOT NULL,
  PRIMARY KEY(id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

create table comments (
  id int(11) NOT NULL AUTO_INCREMENT,
  post_id int(11) NOT NULL, 
  title varchar(255) NOT NULL,
  email varchar(255) NOT NULL,
  body text NOT NULL,
  date_created datetime NOT NULL,
  last_updated datetime NOT NULL,
  PRIMARY KEY(id),
  FOREIGN KEY(post_id) REFERENCES posts(id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
