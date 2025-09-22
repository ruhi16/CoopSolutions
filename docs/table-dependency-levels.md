# CoopSolutions Database Table Dependencies by Level

## **LEVEL 0 - Foundation Tables (No Dependencies)**

### **1. users**
- **Key Fields**: id (PK), email (UNIQUE)
- **Important**: name, password, email_verified_at, remember_token

### **2. ec01_organisations** 
- **Key Fields**: id (PK), name (business identifier)
- **Important**: description, address, phone, is_active, remarks

### **3. ec04_member_types**
- **Key Fields**: id (PK), name (business identifier)
- **Important**: description, name_short, is_active

### **4. roles**
- **Key Fields**: id (PK), name (business identifier)
- **Important**: description, is_active

### **5. ec06_loan_schemes**
- **Key Fields**: id (PK), name (business identifier)
- **Important**: description, start_date, end_date, status (ENUM), is_active

### **6. ec07_loan_scheme_features**
- **Key Fields**: id (PK), name, loan_scheme_feature_name, loan_scheme_feature_type
- **Important**: description, is_required, is_active

### **7. tb01_operations**
- **Key Fields**: id (PK), name (business identifier)
- **Important**: description, is_active

### **8. ec20_banks**
- **Key Fields**: id (PK), name (business identifier)
- **Important**: description, status (ENUM), user_id (FK), organisation_id (FK), financial_year_id (FK), is_finalized

---

## **LEVEL 1 - Single Dependency Tables**

### **9. ec02_financial_years**
- **Dependencies**: ec01_organisations
- **Key Fields**: id (PK), organisation_id (FK), name (business identifier)
- **Important**: start_date, end_date, status (ENUM), is_active

### **10. wf01_task_categories**
- **Dependencies**: ec01_organisations, users
- **Key Fields**: id (PK), organisation_id (FK), name (business identifier)
- **Important**: is_finalized, finalized_by (FK), finalized_at, is_deleted, deleted_by (FK)

### **11. ec20_bank_details**
- **Dependencies**: ec20_banks
- **Key Fields**: id (PK), bank_id (FK)
- **Important**: branch_name, branch_code, ifsc_code, address, contact_person

---

## **LEVEL 2 - Multi-Dependency Tables**

### **12. ec04_members**
- **Dependencies**: ec01_organisations, ec04_member_types
- **Key Fields**: id (PK), organisation_id (FK), member_type_id (FK), name (business identifier)
- **Important**: email, phone, address, pan_no, aadhar_no, account_bank, account_no, is_active

### **13. ec03_organisation_officials**
- **Dependencies**: ec01_organisations, ec04_members
- **Key Fields**: id (PK), organisation_id (FK), member_id (FK)
- **Important**: designation, description, is_active

### **14. wf03_task_events**
- **Dependencies**: wf01_task_categories, roles, ec01_organisations
- **Key Fields**: id (PK), task_category_id (FK), role_id (FK), name (business identifier)
- **Important**: organisation_id (FK), is_finalized, finalized_by (FK), is_deleted

### **15. ec07_loan_scheme_details**
- **Dependencies**: ec06_loan_schemes, ec07_loan_scheme_features
- **Key Fields**: id (PK), loan_scheme_id (FK), loan_scheme_feature_id (FK)
- **Important**: min_amount, max_amount, terms_in_month, main_interest_rate, service_interest_rate

### **16. ec21_bank_loan_schemes**
- **Dependencies**: ec20_banks, ec01_organisations, ec02_financial_years
- **Key Fields**: id (PK), bank_id (FK), name (business identifier)
- **Important**: organisation_id (FK), financial_year_id (FK), status (ENUM), is_finalized

### **17. tb02_role_operations**
- **Dependencies**: roles, tb01_operations
- **Key Fields**: id (PK), role_id (FK), operation_id (FK)
- **Important**: is_allowed (BOOLEAN), is_active

---

## **LEVEL 3 - Complex Dependencies**

### **18. ec08_loan_requests**
- **Dependencies**: ec01_organisations, ec04_members, ec06_loan_schemes, users
- **Key Fields**: id (PK), organisation_id (FK), member_id (FK), req_loan_scheme_id (FK)
- **Important**: req_loan_amount, req_date, time_period_months, status (ENUM), approved_loan_amount, member_concent

### **19. wf02_task_event_particulars**
- **Dependencies**: wf03_task_events, ec01_organisations
- **Key Fields**: id (PK), task_event_id (FK), name (business identifier)
- **Important**: organisation_id (FK), is_finalized, is_deleted

### **20. ec15_thfund_master_dbs**
- **Dependencies**: ec04_members, ec01_organisations, ec02_financial_years
- **Key Fields**: id (PK), member_id (FK), name (business identifier)
- **Important**: thfund_operational_amount, thfund_operational_type (ENUM), thfund_current_balance, organisation_id (FK)

### **21. ec16_shfund_member_master_dbs**
- **Dependencies**: ec04_members, ec01_organisations, ec02_financial_years
- **Key Fields**: id (PK), member_id (FK), name (business identifier)
- **Important**: shfund_operational_amount, shfund_current_balance, organisation_id (FK)

### **22. ec17_shfund_bank_master_dbs**
- **Dependencies**: ec20_banks, ec01_organisations, ec02_financial_years
- **Key Fields**: id (PK), bank_id (FK), name (business identifier)
- **Important**: shfund_operational_amount, shfund_current_balance, organisation_id (FK)

---

## **LEVEL 4 - Transaction & Assignment Tables**

### **23. ec08_loan_assigns**
- **Dependencies**: ec01_organisations, ec04_members, ec08_loan_requests, ec06_loan_schemes
- **Key Fields**: id (PK), organisation_id (FK), member_id (FK), loan_request_id (FK), loan_scheme_id (FK)
- **Important**: loan_amount, start_date, end_date, emi_amount, emi_payment_date

### **24. ec21_bank_loan_borroweds**
- **Dependencies**: ec21_bank_loan_schemes, ec01_organisations, ec02_financial_years
- **Key Fields**: id (PK), bank_loan_scheme_id (FK), name (business identifier)
- **Important**: loan_amount, loan_date, monthly_emi_amount, current_balance_amount, status (ENUM)

### **25. wf05_task_executions**
- **Dependencies**: wf03_task_events, ec01_organisations
- **Key Fields**: id (PK), task_event_id (FK), name (business identifier)
- **Important**: execution_date, status (ENUM), organisation_id (FK), is_finalized

---

## **LEVEL 5 - Schedule & Detail Tables**

### **26. ec10_loan_assign_schedules**
- **Dependencies**: ec08_loan_assigns
- **Key Fields**: id (PK), loan_assign_id (FK), payment_schedule_no (INTEGER)
- **Important**: payment_schedule_date, payment_schedule_status (ENUM), payment_schedule_total_amount, is_paid

### **27. ec09_loan_assign_particulars**
- **Dependencies**: ec08_loan_assigns, ec06_loan_schemes, ec07_loan_scheme_details
- **Key Fields**: id (PK), loan_assign_id (FK), loan_scheme_id (FK), loan_scheme_detail_id (FK)
- **Important**: is_regular, loan_schedule_id

### **28. ec15_thfund_transactions**
- **Dependencies**: ec15_thfund_master_dbs, ec01_organisations, ec02_financial_years
- **Key Fields**: id (PK), thfund_master_db_id (FK), name (business identifier)
- **Important**: transaction_amount, transaction_type (ENUM), transaction_date, balance_after_transaction

### **29. ec16_shfund_member_transactions**
- **Dependencies**: ec16_shfund_member_master_dbs
- **Key Fields**: id (PK), shfund_member_master_db_id (FK)
- **Important**: transaction_amount, transaction_type (ENUM), balance_after_transaction

### **30. ec17_shfund_bank_transactions**
- **Dependencies**: ec17_shfund_bank_master_dbs
- **Key Fields**: id (PK), shfund_bank_master_db_id (FK)
- **Important**: transaction_amount, transaction_type (ENUM), balance_after_transaction

---

## **LEVEL 6 - Payment & Execution Tables**

### **31. ec11_loan_payments**
- **Dependencies**: ec08_loan_assigns, ec04_members, ec10_loan_assign_schedules
- **Key Fields**: id (PK), loan_assign_id (FK), member_id (FK), payment_schedule_id (FK)
- **Important**: payment_total_amount, payment_principal_amount, payment_date, is_paid, principal_balance_amount

### **32. ec23_bank_loan_payments**
- **Dependencies**: ec21_bank_loan_borroweds
- **Key Fields**: id (PK), bank_loan_borrowed_id (FK), name (business identifier)
- **Important**: payment_amount, payment_date, balance_after_payment, payment_type (ENUM)

### **33. wf06_task_execution_phases**
- **Dependencies**: wf05_task_executions
- **Key Fields**: id (PK), task_execution_id (FK), phase_number (INTEGER), name
- **Important**: phase_start_date, phase_end_date, phase_status (ENUM), phase_result

---

## **LEVEL 7 - Detail & Audit Tables**

### **34. ec12_loan_payment_details**
- **Dependencies**: ec08_loan_assigns, ec11_loan_payments, ec10_loan_assign_schedules, ec09_loan_assign_particulars
- **Key Fields**: id (PK), loan_assign_id (FK), loan_payment_id (FK), loan_schedule_id (FK), loan_assign_particular_id (FK)
- **Important**: loan_assign_current_balance_copy, loan_assign_particular_amount, is_scheduled, is_fixed_amount

### **35. ec23_bank_loan_payment_details**
- **Dependencies**: ec23_bank_loan_payments
- **Key Fields**: id (PK), bank_loan_payment_id (FK)
- **Important**: payment_particular_name, payment_particular_amount, payment_particular_type (ENUM)

---

## **Key Business Rules & Constraints**

### **Critical Foreign Key Relationships**
- `organisation_id` → Central to all business operations
- `member_id` → Core to all financial transactions
- `user_id` → Links all operational activities to users
- `financial_year_id` → Provides time-based context

### **Important Status Fields**
- `is_active` (BOOLEAN) - Record status control
- `is_finalized` (BOOLEAN) - Approval workflow
- `is_deleted` (BOOLEAN) - Soft delete mechanism
- `status` (ENUM) - Business process states

### **Financial Amount Fields**
- All amounts stored as DECIMAL for precision
- Balance fields maintain current state
- Transaction fields track operations

### **Audit Trail Fields**
- `created_at`, `updated_at` - Standard Laravel timestamps
- `finalized_by`, `deleted_by` - User tracking
- `finalized_at`, `deleted_at` - Action timestamps