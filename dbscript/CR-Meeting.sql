alter table hs_hr_emp_contract add column status smallint

UPDATE `orangehrm_mysql_test`.`ohrm_user_role_data_group` SET `can_update` = null WHERE (`id` = '43');
UPDATE `orangehrm_mysql_test`.`ohrm_user_role_data_group` SET `can_update` = null WHERE (`id` = '46');
UPDATE `orangehrm_mysql_test`.`ohrm_user_role_data_group` SET `can_update` = null, can_create= null, can_delete = null  WHERE (`id` = '49');
UPDATE `orangehrm_mysql_test`.`ohrm_user_role_data_group` SET `can_update` = null, can_create= null, can_delete = null  WHERE (`id` = '52');

UPDATE `orangehrm_mysql_test`.`ohrm_user_role_data_group` SET `can_update` = null, can_create= null, can_delete = null  
WHERE id in 
(
43,
44,
45,
46,
47,
48,
49,
50,
51,
52,
53,
54,
55,
56,
57,
58,
59,
60,
61,
62,
63,
64,
65,
66,
67,
68,
69,
70,
71,
72,
73,
74,
75,
76,
77,
78,
79,
80,
81,
82,
83,
167,
170,
173,
176,
180,
183,
186,
187,
190,
194,
197,
200,
203,
206,
209,
212,
218,
219,
222,
225,
229,
231,
235,
44,
47,
50,
53,
56,
59,
62,
65,
69,
76,
79
);

UPDATE `orangehrm_mysql_test`.`ohrm_user_role_data_group` SET `can_update` = null, can_create= null, can_delete = null  
WHERE id in 
(
71,
72,
73,
74,
75,
76,
77
);

UPDATE `orangehrm_mysql_test`.`ohrm_user_role_data_group` SET `can_update` = null, can_create= null, can_delete = null  
WHERE id in 
(
112,
153,
113,
154,
114,
155,
115,
156,
116,
157,
117,
158,
118,
159
);

UPDATE `orangehrm_mysql_test`.`ohrm_user_role_data_group` SET `can_update` = null, can_create= null, can_delete = null  
WHERE id in 
(87,
128
);

UPDATE `orangehrm_mysql_test`.`ohrm_user_role_data_group` SET `can_update` = null, can_create= null, can_delete = null  
WHERE id in 
(
93,
134
);

alter table hs_hr_employee add column emp_placeofbirth varchar(50), add column bpjstk varchar(50);

INSERT INTO `ohrm_module` (`name`, `status`) VALUES ('customRecruitment', '1');

INSERT INTO `ohrm_screen` (`name`, `module_id`, `action_url`) VALUES ('Print Leave Report', '4', 'printLeaveReport');

SET @ohrm_screen_id := (SELECT id FROM ohrm_screen WHERE action_url = 'printLeaveReport' LIMIT 1);
INSERT INTO `ohrm_menu_item` (`menu_title`, `screen_id`, `parent_id`, `level`, `order_hint`, `status`) VALUES ('Print Leave Report', @ohrm_screen_id, '78', '3', '100', '1');

INSERT INTO `ohrm_user_role_screen` (`user_role_id`, `screen_id`, `can_read`, `can_create`, `can_update`, `can_delete`) VALUES ('1', '133', '1', '1', '1', '1');

set @module_id = (select id from ohrm_module where name = 'customRecruitment' limit 1 );
INSERT INTO `ohrm_screen` (`name`, `module_id`, `action_url`) VALUES ('Upload CV Candidate', @module_id, 'viewUploadCV');

set @screen_id = (select id from ohrm_screen where name = 'Upload CV Candidate' limit 1 );
INSERT INTO `ohrm_menu_item` (`menu_title`, `screen_id`, `parent_id`, `level`, `order_hint`, `status`) VALUES ('Upload CV', @screen_id, '65', '2', '80', '1');
INSERT INTO `ohrm_user_role_screen` (`user_role_id`, `screen_id`, `can_read`, `can_create`, `can_update`, `can_delete`) VALUES ('1', @screen_id, '1', '1', '1', '1');

INSERT INTO `ohrm_data_group` (`name`, `description`, `can_read`, `can_create`, `can_update`, `can_delete`) VALUES ('upload_cv', 'Recruitment - Upload CV', '1', '1', '1', '1');

set @data_group_id = (select id from ohrm_data_group where name = 'upload_cv' limit 1 );
INSERT INTO `ohrm_user_role_data_group` (`user_role_id`, `data_group_id`, `can_read`, `can_create`, `can_update`, `can_delete`) VALUES ('1', @data_group_id, '1', '1', '1', '1');



