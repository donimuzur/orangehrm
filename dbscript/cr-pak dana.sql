alter table hs_hr_employee add column npwp varchar(50);
alter table hs_hr_employee add column bpjs varchar(50);

CREATE TABLE `hs_hr_emp_contract` (
  `emp_number` int NOT NULL,
  `emp_contract_number` int NOT NULL,
  `emp_contract_start_date` datetime DEFAULT NULL,
  `emp_contract_end_date` datetime DEFAULT NULL,
  `keterangan` text,
  PRIMARY KEY (`emp_number`,`emp_contract_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
