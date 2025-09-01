BEGIN TRANSACTION;
CREATE TABLE IF NOT EXISTS "ec01_organisations" (
	"id"	integer NOT NULL,
	"name"	varchar,
	"description"	varchar,
	"address"	varchar,
	"phone"	varchar,
	"is_active"	tinyint(1) NOT NULL DEFAULT 1,
	"remarks"	varchar NOT NULL,
	"created_at"	datetime,
	"updated_at"	datetime,
	PRIMARY KEY("id" AUTOINCREMENT)
);
CREATE TABLE IF NOT EXISTS "ec02_financial_years" (
	"id"	integer NOT NULL,
	"organisation_id"	INTEGER,
	"name"	varchar,
	"description"	varchar,
	"start_date"	varchar,
	"end_date"	varchar,
	"status"	varchar NOT NULL DEFAULT 'suspended' CHECK("status" IN ('running', 'completed', 'upcoming', 'suspended', 'cancelled')),
	"is_active"	tinyint(1) NOT NULL,
	"remarks"	varchar NOT NULL,
	"created_at"	datetime,
	"updated_at"	datetime,
	PRIMARY KEY("id" AUTOINCREMENT)
);
CREATE TABLE IF NOT EXISTS "ec03_organisation_officials" ("id" integer not null primary key autoincrement, "organisation_id" integer not null, "member_id" integer, "designation" varchar, "description" varchar, "is_active" tinyint(1) not null, "remarks" varchar not null, "created_at" datetime, "updated_at" datetime);
CREATE TABLE IF NOT EXISTS "ec04_members" (
	"id"	integer NOT NULL,
	"organisation_id"	varchar,
	"member_type_id"	integer,
	"name"	varchar,
	"name_short"	varchar,
	"email"	varchar,
	"phone"	varchar,
	"mobile"	varchar,
	"address"	varchar,
	"dob"	varchar,
	"gender"	varchar,
	"nationality"	varchar,
	"religion"	varchar,
	"marital_status"	varchar,
	"blood_group"	varchar,
	"pan_no"	varchar,
	"aadhar_no"	varchar,
	"account_bank"	varchar,
	"account_branch"	varchar,
	"account_no"	varchar,
	"account_ifsc"	varchar,
	"account_micr"	varchar,
	"account_customer_id"	varchar,
	"account_holder_name"	varchar,
	"is_active"	tinyint(1) NOT NULL DEFAULT 1,
	"remarks"	varchar,
	"created_at"	datetime,
	"updated_at"	datetime,
	PRIMARY KEY("id" AUTOINCREMENT)
);
CREATE TABLE IF NOT EXISTS "ec05_member_types" (
	"id"	integer NOT NULL,
	"name"	varchar,
	"description"	varchar,
	"is_active"	tinyint(1),
	"remarks"	varchar,
	"created_at"	datetime,
	"updated_at"	datetime,
	PRIMARY KEY("id" AUTOINCREMENT)
);
CREATE TABLE IF NOT EXISTS "ec06_loan_schemes" (
	"id"	integer NOT NULL,
	"name"	varchar,
	"description"	varchar,
	"name_short"	varchar,
	"start_date"	date,
	"end_date"	date,
	"status"	varchar DEFAULT 'suspended' CHECK("status" IN ('running', 'completed', 'upcoming', 'suspended', 'cancelled')),
	"is_active"	tinyint(1),
	"remarks"	varchar,
	"created_at"	datetime,
	"updated_at"	datetime,
	PRIMARY KEY("id" AUTOINCREMENT)
);
CREATE TABLE IF NOT EXISTS "ec07_loan_scheme_details" (
	"id"	integer NOT NULL,
	"loan_scheme_id"	integer NOT NULL,
	"loan_scheme_feature_id"	INTEGER,
	"loan_scheme_feature_standard_id"	INTEGER,
	"loan_scheme_feature_value"	TEXT,
	"min_amount"	float,
	"max_amount"	float,
	"terms_in_month"	integer,
	"main_interest_rate"	float,
	"service_interest_rate"	REAL,
	"schedule_type"	varchar,
	"is_active"	tinyint(1) NOT NULL DEFAULT '1',
	"remarks"	varchar,
	"created_at"	datetime,
	"updated_at"	datetime,
	PRIMARY KEY("id" AUTOINCREMENT)
);
CREATE TABLE IF NOT EXISTS "ec07_loan_scheme_feature_standards" ("id" integer not null primary key autoincrement, "loan_scheme_id" integer, "loan_scheme_feature_id" integer, "loan_scheme_feature_value" integer, "is_required" tinyint(1) not null default '1', "is_active" tinyint(1) not null default '1', "remarks" varchar, "created_at" datetime, "updated_at" datetime);
CREATE TABLE IF NOT EXISTS "ec07_loan_scheme_features" ("id" integer not null primary key autoincrement, "name" varchar not null, "description" varchar not null, "loan_scheme_feature_name" varchar not null, "loan_scheme_feature_type" varchar not null, "is_required" tinyint(1) not null default '1', "is_active" tinyint(1) not null default '1', "remarks" varchar, "created_at" datetime, "updated_at" datetime);
CREATE TABLE IF NOT EXISTS "ec08_loan_assigns" ("id" integer not null primary key autoincrement, "organisation_id" integer not null, "member_id" integer not null, "loan_request_id" integer not null, "loan_scheme_id" integer not null, "loan_amount" float, "start_date" date, "end_date" date, "emi_payment_date" date, "emi_amount" float, "is_active" tinyint(1) not null default '1', "remarks" varchar, "created_at" datetime, "updated_at" datetime);
CREATE TABLE IF NOT EXISTS "ec08_loan_requests" (
	"id"	integer NOT NULL,
	"organisation_id"	NUMERIC,
	"member_id"	BLOB,
	"req_loan_scheme_id"	integer,
	"req_loan_amount"	float,
	"req_date"	date,
	"time_period_months"	INTEGER,
	"status"	varchar DEFAULT 'pending' CHECK("status" IN ('pending', 'approved', 'rejected', 'cancelled', 'closed', 'expired', 'overdue', 'completed')),
	"status_closed_date"	date,
	"status_assigning_date"	date,
	"status_instructions"	varchar,
	"approved_loan_amount"	float,
	"approved_loan_amount_date"	date,
	"member_concent"	tinyint(1) DEFAULT '0',
	"member_concent_note"	varchar,
	"member_concent_date"	date,
	"done_by"	integer,
	"is_active"	tinyint(1) DEFAULT '1',
	"remarks"	varchar,
	"created_at"	datetime,
	"updated_at"	datetime,
	PRIMARY KEY("id" AUTOINCREMENT)
);
CREATE TABLE IF NOT EXISTS "ec09_loan_assign_particulars" ("id" integer not null primary key autoincrement, "loan_assign_id" integer, "loan_scheme_id" integer, "loan_scheme_detail_id" integer, "is_regular" tinyint(1) not null default '0', "loan_schedule_id" integer, "is_active" tinyint(1) not null default '1', "remarks" varchar, "created_at" datetime, "updated_at" datetime);
CREATE TABLE IF NOT EXISTS "ec10_loan_assign_schedules" ("id" integer not null primary key autoincrement, "loan_assign_id" integer not null, "payment_schedule_no" integer, "payment_schedule_date" date, "payment_schedule_status" varchar check ("payment_schedule_status" in ('pending', 'completed', 'suspended')), "payment_schedule_balance_amount_copy" float, "payment_schedule_total_amount" float, "payment_schedule_principal" float, "payment_schedule_interest" float, "payment_schedule_others" float, "is_paid" tinyint(1) not null default '0', "is_active" tinyint(1) not null default '1', "remarks" varchar, "created_at" datetime, "updated_at" datetime);
CREATE TABLE IF NOT EXISTS "ec11_loan_payments" ("id" integer not null primary key autoincrement, "loan_assign_id" integer not null, "member_id" integer not null, "payment_schedule_id" integer not null, "payment_total_amount" float, "payment_principal_amount" float, "regular_amount_total" float, "scheduled_amount_total" float, "payment_date" date, "is_paid" tinyint(1) not null default '0', "principal_balance_amount" float, "is_active" tinyint(1) not null default '1', "remarks" varchar, "created_at" datetime, "updated_at" datetime);
CREATE TABLE IF NOT EXISTS "ec12_loan_payment_details" ("id" integer not null primary key autoincrement, "loan_assign_id" integer, "loan_payment_id" integer, "loan_schedule_id" integer, "loan_assign_particular_id" integer, "loan_assign_current_balance_copy" float default '0', "loan_assign_particular_amount" integer, "is_scheduled" tinyint(1) not null default '0', "is_fixed_amount" tinyint(1) not null default '0', "is_active" tinyint(1) not null default '1', "remarks" varchar, "created_at" datetime, "updated_at" datetime);
CREATE TABLE IF NOT EXISTS "failed_jobs" ("id" integer not null primary key autoincrement, "uuid" varchar not null, "connection" text not null, "queue" text not null, "payload" text not null, "exception" text not null, "failed_at" datetime default CURRENT_TIMESTAMP not null);
CREATE TABLE IF NOT EXISTS "migrations" ("id" integer not null primary key autoincrement, "migration" varchar not null, "batch" integer not null);
CREATE TABLE IF NOT EXISTS "password_resets" ("email" varchar not null, "token" varchar not null, "created_at" datetime);
CREATE TABLE IF NOT EXISTS "personal_access_tokens" ("id" integer not null primary key autoincrement, "tokenable_type" varchar not null, "tokenable_id" integer not null, "name" varchar not null, "token" varchar not null, "abilities" text, "last_used_at" datetime, "created_at" datetime, "updated_at" datetime);
CREATE TABLE IF NOT EXISTS "users" ("id" integer not null primary key autoincrement, "name" varchar not null, "email" varchar not null, "email_verified_at" datetime, "password" varchar not null, "remember_token" varchar, "created_at" datetime, "updated_at" datetime);
INSERT INTO "ec01_organisations" ("id","name","description","address","phone","is_active","remarks","created_at","updated_at") VALUES (1,'Manikchak HM ECCS LTD','','','',1,'','','');
INSERT INTO "ec02_financial_years" ("id","organisation_id","name","description","start_date","end_date","status","is_active","remarks","created_at","updated_at") VALUES (1,1,'2023-24','','','','suspended',0,'','',''),
 (2,1,'2024-25','','','','suspended',0,'','',''),
 (3,1,'2025-26',NULL,NULL,NULL,'suspended',1,'',NULL,NULL);
INSERT INTO "ec03_organisation_officials" ("id","organisation_id","member_id","designation","description","is_active","remarks","created_at","updated_at") VALUES (1,1,1,'Secretary','',1,'','',''),
 (2,1,10,'President','',1,'',NULL,NULL),
 (3,1,4,'Director','',1,'',NULL,NULL),
 (4,1,14,'Gen Member','',1,'',NULL,NULL),
 (5,1,26,'Gen Member','',1,'',NULL,NULL),
 (6,1,3,'Gen Member',NULL,1,'',NULL,NULL);
INSERT INTO "ec04_members" ("id","organisation_id","member_type_id","name","name_short","email","phone","mobile","address","dob","gender","nationality","religion","marital_status","blood_group","pan_no","aadhar_no","account_bank","account_branch","account_no","account_ifsc","account_micr","account_customer_id","account_holder_name","is_active","remarks","created_at","updated_at") VALUES (1,'1',1,'NARAYAN BARMAN','NB','','','','','','','','','','','','','','','1555','','','','',1,'','2025-07-07 02:40:58','2025-07-07 02:40:58'),
 (2,'1',1,'GANESH CHANDRA MONDAL','GCM',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'',NULL,NULL),
 (3,'1',1,'ABDUL MOMEN','AM',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'',NULL,NULL),
 (4,'1',1,'NAVID ANJUM','NA','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'',NULL,NULL),
 (5,'1',1,'SOUMEN MONDAL','SM',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'',NULL,NULL),
 (6,'1',1,'MD ABDUR ROUF','AR','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'',NULL,NULL),
 (7,'1',1,'MD MUKHLESUR RAHAMAN','MR','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'',NULL,NULL),
 (8,'1',1,'MD SARIFUJJAMAN','SJ',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'',NULL,NULL),
 (9,'1',1,'RAJES UPADHYAY','RU',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'',NULL,NULL),
 (10,'1',1,'HARI NARAYAN DAS','HND','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'',NULL,NULL),
 (11,'1',1,'DEBASIS ROY','DR','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'',NULL,NULL),
 (12,'1',1,'MD ABUL FAJAL','AF',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'',NULL,NULL),
 (13,'1',1,'MD SABIR AHAMMED','S AH',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'',NULL,NULL),
 (14,'1',1,'SABIR ALI','S ALI',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'',NULL,NULL),
 (15,'1',1,'SK ABDUL AZIZ','AZ','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'',NULL,NULL),
 (16,'1',1,'SHUBHANKAR BHATTACHARIA','SB',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'',NULL,NULL),
 (17,'1',1,'MD ANSARUL HAQUE','AH','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'',NULL,NULL),
 (18,'1',1,'MD HAJEKUL SK','HS','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'',NULL,NULL),
 (19,'1',1,'MD GOLAM MOJADDID','GM','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'',NULL,NULL),
 (20,'1',1,'MD GAJIJUK ISLAM','GI','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'',NULL,NULL),
 (21,'1',1,'MD MASUM REJA','MR','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'',NULL,NULL),
 (22,'1',2,'ISMAIL HOQUE','IH',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'',NULL,NULL),
 (23,'1',2,'MD ZIAUL HOQUE','ZH',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'',NULL,NULL),
 (24,'1',1,'MD SAMSAD HOSSAIN','SH',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'',NULL,NULL),
 (25,'1',3,'MD RAFIQUL HASAN','RH','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'',NULL,NULL),
 (26,'1',1,'ARJINA KHATUN','AK','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'',NULL,NULL),
 (27,'1',1,'MD JAMALUDDIN','JA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'',NULL,NULL),
 (28,'1',2,'RAKHI KHATUN','RK',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'',NULL,NULL),
 (29,'1',1,'MD RAFIKUL ISLAM','RI','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'',NULL,NULL),
 (30,'1',1,'MD SAHABUDDIN SK','SK','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'',NULL,NULL),
 (31,'1',1,'SAMIMA KHATUN','SK',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'',NULL,NULL),
 (32,'1',1,'MD GOLAM KIBRIA','GK',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'',NULL,NULL),
 (40,'1',NULL,'Hari Narayan Das',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'18545',NULL,NULL,NULL,NULL,1,NULL,'2025-07-08 07:50:48','2025-07-08 07:50:48');
INSERT INTO "ec05_member_types" ("id","name","description","is_active","remarks","created_at","updated_at") VALUES (1,'Asst Teacher','','','','',''),
 (2,'Para Teacher','','','','',''),
 (3,'Contractual Teacher','','','','',''),
 (4,'dsfsf','dfsfsdf',1,NULL,'2025-07-08 10:43:42','2025-07-08 10:43:42');
INSERT INTO "ec06_loan_schemes" ("id","name","description","name_short","start_date","end_date","status","is_active","remarks","created_at","updated_at") VALUES (1,'ST','st','','','','suspended','','','',''),
 (2,'MT','mt','','','','suspended','','','',''),
 (3,'MT-EMI','mt-emi',NULL,NULL,NULL,'suspended','','',NULL,NULL),
 (4,'ST-EMI','Detail',NULL,NULL,NULL,'suspended',NULL,NULL,'2025-07-21 10:18:43','2025-07-21 18:55:16'),
 (5,'ST','stt48',NULL,NULL,NULL,'suspended',NULL,NULL,'2025-07-21 17:46:01','2025-07-21 17:56:58');
INSERT INTO "ec07_loan_scheme_details" ("id","loan_scheme_id","loan_scheme_feature_id","loan_scheme_feature_standard_id","loan_scheme_feature_value","min_amount","max_amount","terms_in_month","main_interest_rate","service_interest_rate","schedule_type","is_active","remarks","created_at","updated_at") VALUES (1,1,1,NULL,'13.2',5000.0,75000.0,36,11.0,0.0,'regular',1,'','','2025-07-25 02:40:57'),
 (2,2,2,NULL,'7',100000.0,1500000.0,84,10.75,0.5,'regular',1,NULL,NULL,'2025-07-25 02:51:13'),
 (6,3,3,NULL,'25000',NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-07-25 02:55:53','2025-07-25 02:56:02'),
 (7,1,1,NULL,'12',NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-07-25 08:27:00','2025-07-25 08:27:00'),
 (8,1,2,NULL,'5000',NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2025-07-25 10:26:32','2025-07-25 10:26:32');
INSERT INTO "ec07_loan_scheme_features" ("id","name","description","loan_scheme_feature_name","loan_scheme_feature_type","is_required","is_active","remarks","created_at","updated_at") VALUES (1,'ROI','','Rate of Interest','Percent per year',1,1,NULL,NULL,NULL),
 (2,'SCH','','Service Charge','Percent per year',1,1,NULL,NULL,NULL),
 (3,'MIN','','Min Amount','Fixed Amount',1,1,'','',''),
 (4,'MAX','','Max Amount','Fixed Amount',1,1,'','',''),
 (5,'Fine','','Fine Amount','Percent on Balance',1,1,NULL,NULL,NULL);
INSERT INTO "ec08_loan_requests" ("id","organisation_id","member_id","req_loan_scheme_id","req_loan_amount","req_date","time_period_months","status","status_closed_date","status_assigning_date","status_instructions","approved_loan_amount","approved_loan_amount_date","member_concent","member_concent_note","member_concent_date","done_by","is_active","remarks","created_at","updated_at") VALUES (1,1,12,3,50000.0,'2025-07-28 02:29:36',36,'pending',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,1,NULL,NULL,'2025-07-28 02:29:36'),
 (2,1,14,2,150000.0,'2025-07-26 03:09:19',48,'pending',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,1,NULL,'2025-07-26 03:09:19','2025-07-26 03:09:19'),
 (4,1,'2',3,20000.0,'2025-07-26 03:09:19',12,'pending',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,1,NULL,'2025-07-27 17:21:52','2025-07-28 01:41:12'),
 (5,1,'4',3,50001.0,'2025-07-28 01:43:34',60,'pending',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,1,NULL,'2025-07-28 01:37:07','2025-07-28 01:43:34'),
 (6,1,'1',2,25000.0,'2025-07-28 02:36:13',24,'pending',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,1,NULL,'2025-07-28 01:58:45','2025-07-28 02:36:13'),
 (7,1,'3',5,15000.0,'2025-07-28 02:33:34',36,'pending',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,1,NULL,'2025-07-28 02:29:54','2025-07-28 02:33:34');
INSERT INTO "migrations" ("id","migration","batch") VALUES (1,'2014_10_12_000000_create_users_table',1),
 (2,'2014_10_12_100000_create_password_resets_table',1),
 (3,'2019_08_19_000000_create_failed_jobs_table',1),
 (4,'2019_12_14_000001_create_personal_access_tokens_table',1),
 (5,'2025_07_05_053828_create_ec01_organisations_table',2),
 (6,'2025_07_05_055414_create_ec02_financial_years_table',2),
 (7,'2025_07_05_060656_create_ec03_organisation_officials_table',2),
 (8,'2025_07_05_060715_create_ec04_members_table',2),
 (9,'2025_07_05_060802_create_ec05_member_types_table',2),
 (10,'2025_07_05_062328_create_ec06_loan_schemes_table',2),
 (11,'2025_07_05_062347_create_ec07_loan_scheme_details_table',2),
 (12,'2025_07_05_062439_create_ec08_loan_assigns_table',2),
 (13,'2025_07_05_062455_create_ec09_loan_assign_particulars_table',2),
 (14,'2025_07_05_062511_create_ec10_loan_assign_schedules_table',2),
 (15,'2025_07_05_063126_create_ec11_loan_payments_table',3),
 (16,'2025_07_05_063213_create_ec12_loan_payment_details_table',3),
 (17,'2025_07_06_032021_create_ec08_loan_requests_table',3),
 (18,'2025_07_21_180511_create_ec07_loan_scheme_features_table',4),
 (19,'2025_07_22_020945_create_ec07_loan_scheme_feature_standards_table',5);
INSERT INTO "users" ("id","name","email","email_verified_at","password","remember_token","created_at","updated_at") VALUES (1,'Hari','admin@gmail.com',NULL,'$2y$10$LNfwuHK3SCAzec5zGfHa7Oyf5xid7BpqTrA99mkM/rHhZfqku1M7W','ByJFB7Wd29NV6k3BiV4hRfObBmFSwVWjbmSLQZEKb2Bf5MmihH5oI4tjTVH6','2025-06-14 05:10:03','2025-06-14 05:10:03');
CREATE INDEX "ec04_members_organisation_id_type_organisation_id_id_index" ON "ec04_members" (
	"organisation_id"
);
CREATE UNIQUE INDEX "failed_jobs_uuid_unique" on "failed_jobs" ("uuid");
CREATE INDEX "password_resets_email_index" on "password_resets" ("email");
CREATE UNIQUE INDEX "personal_access_tokens_token_unique" on "personal_access_tokens" ("token");
CREATE INDEX "personal_access_tokens_tokenable_type_tokenable_id_index" on "personal_access_tokens" ("tokenable_type", "tokenable_id");
CREATE UNIQUE INDEX "users_email_unique" on "users" ("email");
COMMIT;
