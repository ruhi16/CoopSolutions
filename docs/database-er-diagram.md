# CoopSolutions Database Entity Relationship Diagram

## Complete ER Diagram

```mermaid
erDiagram
    %% Foundation Tables (Level 0)
    users {
        int id PK
        string name
        string email
        timestamp email_verified_at
        string password
        string remember_token
        timestamps created_at
        timestamps updated_at
    }

    ec01_organisations {
        int id PK
        string name
        string description
        string address
        string phone
        boolean is_active
        string remarks
        timestamps created_at
        timestamps updated_at
    }

    ec04_member_types {
        int id PK
        string name
        string description
        string name_short
        string remarks
        boolean is_active
        timestamps created_at
        timestamps updated_at
    }

    roles {
        int id PK
        string name
        string description
        boolean is_active
        string remarks
        timestamps created_at
        timestamps updated_at
    }

    ec06_loan_schemes {
        int id PK
        string name
        string description
        string name_short
        date start_date
        date end_date
        enum status
        boolean is_active
        string remarks
        timestamps created_at
        timestamps updated_at
    }

    ec07_loan_scheme_features {
        int id PK
        string name
        string description
        string loan_scheme_feature_name
        string loan_scheme_feature_type
        boolean is_required
        boolean is_active
        string remarks
        timestamps created_at
        timestamps updated_at
    }

    ec20_banks {
        int id PK
        string name
        string description
        enum status
        int user_id FK
        int organisation_id FK
        int financial_year_id FK
        boolean is_finalized
        int finalized_by FK
        date finalized_at
        boolean is_active
        string remarks
        timestamps created_at
        timestamps updated_at
    }

    tb01_operations {
        int id PK
        string name
        string description
        boolean is_active
        string remarks
        timestamps created_at
        timestamps updated_at
    }

    %% Level 1 Tables
    ec02_financial_years {
        int id PK
        int organisation_id FK
        string name
        string description
        string start_date
        string end_date
        enum status
        boolean is_active
        string remarks
        timestamps created_at
        timestamps updated_at
    }

    wf01_task_categories {
        int id PK
        string name
        string description
        boolean is_finalized
        int finalized_by FK
        date finalized_at
        boolean is_deleted
        int deleted_by FK
        date deleted_at
        int organisation_id FK
        boolean is_active
        string remarks
        timestamps created_at
        timestamps updated_at
    }

    ec20_bank_details {
        int id PK
        int bank_id FK
        string branch_name
        string branch_code
        string ifsc_code
        string micr_code
        string address
        string contact_person
        string phone
        string email
        boolean is_active
        string remarks
        timestamps created_at
        timestamps updated_at
    }

    %% Level 2 Tables
    ec04_members {
        int id PK
        int organisation_id FK
        int member_type_id FK
        string name
        string name_short
        string email
        string phone
        string mobile
        string address
        string dob
        string gender
        string nationality
        string religion
        string marital_status
        string blood_group
        string pan_no
        string aadhar_no
        string account_bank
        string account_branch
        string account_no
        string account_ifsc
        string account_micr
        string account_customer_id
        string account_holder_name
        boolean is_active
        string remarks
        timestamps created_at
        timestamps updated_at
    }

    ec03_organisation_officials {
        int id PK
        int organisation_id FK
        int member_id FK
        string designation
        string description
        boolean is_active
        string remarks
        timestamps created_at
        timestamps updated_at
    }

    wf03_task_events {
        int id PK
        string name
        string description
        int task_category_id FK
        int role_id FK
        boolean is_finalized
        int finalized_by FK
        date finalized_at
        boolean is_deleted
        int deleted_by FK
        date deleted_at
        int organisation_id FK
        boolean is_active
        string remarks
        timestamps created_at
        timestamps updated_at
    }

    ec07_loan_scheme_details {
        int id PK
        int loan_scheme_id FK
        int loan_scheme_feature_id FK
        int loan_scheme_feature_standard_id FK
        string loan_scheme_feature_value
        double min_amount
        double max_amount
        int terms_in_month
        double main_interest_rate
        double service_interest_rate
        string schedule_type
        boolean is_active
        string remarks
        timestamps created_at
        timestamps updated_at
    }

    ec21_bank_loan_schemes {
        int id PK
        string name
        string description
        int bank_id FK
        date effected_on
        enum status
        int task_execution_id FK
        int user_id FK
        int organisation_id FK
        int financial_year_id FK
        boolean is_finalized
        int finalized_by FK
        date finalized_at
        boolean is_active
        string remarks
        timestamps created_at
        timestamps updated_at
    }

    tb02_role_operations {
        int id PK
        int role_id FK
        int operation_id FK
        boolean is_allowed
        boolean is_active
        string remarks
        timestamps created_at
        timestamps updated_at
    }

    %% Level 3 Tables
    ec08_loan_requests {
        int id PK
        int organisation_id FK
        int member_id FK
        int req_loan_scheme_id FK
        double req_loan_amount
        date req_date
        int time_period_months
        enum status
        date status_closed_date
        date status_assigning_date
        string status_instructions
        double approved_loan_amount
        date approved_loan_amount_date
        boolean member_concent
        string member_concent_note
        date member_concent_date
        int done_by FK
        boolean is_active
        string remarks
        timestamps created_at
        timestamps updated_at
    }

    wf02_task_event_particulars {
        int id PK
        string name
        string description
        int task_event_id FK
        boolean is_finalized
        int finalized_by FK
        date finalized_at
        boolean is_deleted
        int deleted_by FK
        date deleted_at
        int organisation_id FK
        boolean is_active
        string remarks
        timestamps created_at
        timestamps updated_at
    }

    ec15_thfund_master_dbs {
        int id PK
        string name
        string description
        int member_id FK
        double thfund_operational_amount
        enum thfund_operational_type
        date thfund_operational_date
        double thfund_current_balance
        int task_execution_id FK
        int user_id FK
        int organisation_id FK
        int financial_year_id FK
        date start_at
        date end_at
        int no_of_months
        enum status
        boolean is_finalized
        int finalized_by FK
        date finalized_at
        boolean is_active
        string remarks
        timestamps created_at
        timestamps updated_at
    }

    ec16_shfund_member_master_dbs {
        int id PK
        string name
        string description
        int member_id FK
        double shfund_operational_amount
        enum shfund_operational_type
        date shfund_operational_date
        double shfund_current_balance
        int task_execution_id FK
        int user_id FK
        int organisation_id FK
        int financial_year_id FK
        date start_at
        date end_at
        int no_of_months
        enum status
        boolean is_finalized
        int finalized_by FK
        date finalized_at
        boolean is_active
        string remarks
        timestamps created_at
        timestamps updated_at
    }

    ec17_shfund_bank_master_dbs {
        int id PK
        string name
        string description
        int bank_id FK
        double shfund_operational_amount
        enum shfund_operational_type
        date shfund_operational_date
        double shfund_current_balance
        int task_execution_id FK
        int user_id FK
        int organisation_id FK
        int financial_year_id FK
        date start_at
        date end_at
        int no_of_months
        enum status
        boolean is_finalized
        int finalized_by FK
        date finalized_at
        boolean is_active
        string remarks
        timestamps created_at
        timestamps updated_at
    }

    %% Level 4 Tables
    ec08_loan_assigns {
        int id PK
        int organisation_id FK
        int member_id FK
        int loan_request_id FK
        int loan_scheme_id FK
        double loan_amount
        date start_date
        date end_date
        date emi_payment_date
        double emi_amount
        boolean is_active
        string remarks
        timestamps created_at
        timestamps updated_at
    }

    ec21_bank_loan_borroweds {
        int id PK
        string name
        string description
        int bank_loan_scheme_id FK
        double loan_amount
        date loan_date
        int time_period_months
        double monthly_emi_amount
        date monthly_emi_start_date
        double current_balance_amount
        enum status
        int task_execution_id FK
        int user_id FK
        int organisation_id FK
        int financial_year_id FK
        boolean is_finalized
        int finalized_by FK
        date finalized_at
        boolean is_active
        string remarks
        timestamps created_at
        timestamps updated_at
    }

    wf05_task_executions {
        int id PK
        string name
        string description
        int task_event_id FK
        date execution_date
        enum status
        boolean is_finalized
        int finalized_by FK
        date finalized_at
        boolean is_deleted
        int deleted_by FK
        date deleted_at
        int organisation_id FK
        boolean is_active
        string remarks
        timestamps created_at
        timestamps updated_at
    }

    %% Level 5 Tables
    ec10_loan_assign_schedules {
        int id PK
        int loan_assign_id FK
        int payment_schedule_no
        date payment_schedule_date
        enum payment_schedule_status
        double payment_schedule_balance_amount_copy
        double payment_schedule_total_amount
        double payment_schedule_principal
        double payment_schedule_interest
        double payment_schedule_others
        boolean is_paid
        boolean is_active
        string remarks
        timestamps created_at
        timestamps updated_at
    }

    ec09_loan_assign_particulars {
        int id PK
        int loan_assign_id FK
        int loan_scheme_id FK
        int loan_scheme_detail_id FK
        boolean is_regular
        int loan_schedule_id FK
        boolean is_active
        string remarks
        timestamps created_at
        timestamps updated_at
    }

    ec15_thfund_transactions {
        int id PK
        string name
        string description
        int thfund_master_db_id FK
        double transaction_amount
        enum transaction_type
        date transaction_date
        double balance_after_transaction
        int task_execution_id FK
        int user_id FK
        int organisation_id FK
        int financial_year_id FK
        boolean is_finalized
        int finalized_by FK
        date finalized_at
        boolean is_active
        string remarks
        timestamps created_at
        timestamps updated_at
    }

    %% Level 6 Tables
    ec11_loan_payments {
        int id PK
        int loan_assign_id FK
        int member_id FK
        int payment_schedule_id FK
        double payment_total_amount
        double payment_principal_amount
        double regular_amount_total
        double scheduled_amount_total
        date payment_date
        boolean is_paid
        double principal_balance_amount
        boolean is_active
        string remarks
        timestamps created_at
        timestamps updated_at
    }

    ec23_bank_loan_payments {
        int id PK
        string name
        string description
        int bank_loan_borrowed_id FK
        double payment_amount
        date payment_date
        double balance_after_payment
        enum payment_type
        int task_execution_id FK
        int user_id FK
        int organisation_id FK
        int financial_year_id FK
        boolean is_finalized
        int finalized_by FK
        date finalized_at
        boolean is_active
        string remarks
        timestamps created_at
        timestamps updated_at
    }

    wf06_task_execution_phases {
        int id PK
        string name
        string description
        int task_execution_id FK
        int phase_number
        date phase_start_date
        date phase_end_date
        enum phase_status
        string phase_result
        boolean is_finalized
        int finalized_by FK
        date finalized_at
        boolean is_deleted
        int deleted_by FK
        date deleted_at
        int organisation_id FK
        boolean is_active
        string remarks
        timestamps created_at
        timestamps updated_at
    }

    %% Level 7 Tables
    ec12_loan_payment_details {
        int id PK
        int loan_assign_id FK
        int loan_payment_id FK
        int loan_schedule_id FK
        int loan_assign_particular_id FK
        double loan_assign_current_balance_copy
        int loan_assign_particular_amount
        boolean is_scheduled
        boolean is_fixed_amount
        boolean is_active
        string remarks
        timestamps created_at
        timestamps updated_at
    }

    %% Relationships
    
    %% Level 0 to Level 1 Relationships
    ec01_organisations ||--o{ ec02_financial_years : "organisation_id"
    ec01_organisations ||--o{ wf01_task_categories : "organisation_id"
    ec20_banks ||--o{ ec20_bank_details : "bank_id"
    users ||--o{ ec20_banks : "user_id"
    ec01_organisations ||--o{ ec20_banks : "organisation_id"
    ec02_financial_years ||--o{ ec20_banks : "financial_year_id"
    users ||--o{ wf01_task_categories : "finalized_by"
    users ||--o{ wf01_task_categories : "deleted_by"

    %% Level 1 to Level 2 Relationships
    ec01_organisations ||--o{ ec04_members : "organisation_id"
    ec04_member_types ||--o{ ec04_members : "member_type_id"
    ec01_organisations ||--o{ ec03_organisation_officials : "organisation_id"
    ec04_members ||--o{ ec03_organisation_officials : "member_id"
    wf01_task_categories ||--o{ wf03_task_events : "task_category_id"
    roles ||--o{ wf03_task_events : "role_id"
    ec01_organisations ||--o{ wf03_task_events : "organisation_id"
    ec06_loan_schemes ||--o{ ec07_loan_scheme_details : "loan_scheme_id"
    ec07_loan_scheme_features ||--o{ ec07_loan_scheme_details : "loan_scheme_feature_id"
    ec20_banks ||--o{ ec21_bank_loan_schemes : "bank_id"
    ec01_organisations ||--o{ ec21_bank_loan_schemes : "organisation_id"
    ec02_financial_years ||--o{ ec21_bank_loan_schemes : "financial_year_id"
    users ||--o{ ec21_bank_loan_schemes : "user_id"
    roles ||--o{ tb02_role_operations : "role_id"
    tb01_operations ||--o{ tb02_role_operations : "operation_id"

    %% Level 2 to Level 3 Relationships
    ec01_organisations ||--o{ ec08_loan_requests : "organisation_id"
    ec04_members ||--o{ ec08_loan_requests : "member_id"
    ec06_loan_schemes ||--o{ ec08_loan_requests : "req_loan_scheme_id"
    users ||--o{ ec08_loan_requests : "done_by"
    wf03_task_events ||--o{ wf02_task_event_particulars : "task_event_id"
    ec04_members ||--o{ ec15_thfund_master_dbs : "member_id"
    ec01_organisations ||--o{ ec15_thfund_master_dbs : "organisation_id"
    ec02_financial_years ||--o{ ec15_thfund_master_dbs : "financial_year_id"
    users ||--o{ ec15_thfund_master_dbs : "user_id"
    ec04_members ||--o{ ec16_shfund_member_master_dbs : "member_id"
    ec01_organisations ||--o{ ec16_shfund_member_master_dbs : "organisation_id"
    ec02_financial_years ||--o{ ec16_shfund_member_master_dbs : "financial_year_id"
    users ||--o{ ec16_shfund_member_master_dbs : "user_id"
    ec20_banks ||--o{ ec17_shfund_bank_master_dbs : "bank_id"
    ec01_organisations ||--o{ ec17_shfund_bank_master_dbs : "organisation_id"
    ec02_financial_years ||--o{ ec17_shfund_bank_master_dbs : "financial_year_id"
    users ||--o{ ec17_shfund_bank_master_dbs : "user_id"

    %% Level 3 to Level 4 Relationships
    ec01_organisations ||--o{ ec08_loan_assigns : "organisation_id"
    ec04_members ||--o{ ec08_loan_assigns : "member_id"
    ec08_loan_requests ||--|| ec08_loan_assigns : "loan_request_id"
    ec06_loan_schemes ||--o{ ec08_loan_assigns : "loan_scheme_id"
    ec21_bank_loan_schemes ||--o{ ec21_bank_loan_borroweds : "bank_loan_scheme_id"
    ec01_organisations ||--o{ ec21_bank_loan_borroweds : "organisation_id"
    ec02_financial_years ||--o{ ec21_bank_loan_borroweds : "financial_year_id"
    users ||--o{ ec21_bank_loan_borroweds : "user_id"
    wf03_task_events ||--o{ wf05_task_executions : "task_event_id"
    ec01_organisations ||--o{ wf05_task_executions : "organisation_id"

    %% Level 4 to Level 5 Relationships
    ec08_loan_assigns ||--o{ ec10_loan_assign_schedules : "loan_assign_id"
    ec08_loan_assigns ||--o{ ec09_loan_assign_particulars : "loan_assign_id"
    ec06_loan_schemes ||--o{ ec09_loan_assign_particulars : "loan_scheme_id"
    ec07_loan_scheme_details ||--o{ ec09_loan_assign_particulars : "loan_scheme_detail_id"
    ec15_thfund_master_dbs ||--o{ ec15_thfund_transactions : "thfund_master_db_id"
    ec01_organisations ||--o{ ec15_thfund_transactions : "organisation_id"
    ec02_financial_years ||--o{ ec15_thfund_transactions : "financial_year_id"
    users ||--o{ ec15_thfund_transactions : "user_id"

    %% Level 5 to Level 6 Relationships
    ec08_loan_assigns ||--o{ ec11_loan_payments : "loan_assign_id"
    ec04_members ||--o{ ec11_loan_payments : "member_id"
    ec10_loan_assign_schedules ||--o{ ec11_loan_payments : "payment_schedule_id"
    ec21_bank_loan_borroweds ||--o{ ec23_bank_loan_payments : "bank_loan_borrowed_id"
    ec01_organisations ||--o{ ec23_bank_loan_payments : "organisation_id"
    ec02_financial_years ||--o{ ec23_bank_loan_payments : "financial_year_id"
    users ||--o{ ec23_bank_loan_payments : "user_id"
    wf05_task_executions ||--o{ wf06_task_execution_phases : "task_execution_id"
    ec01_organisations ||--o{ wf06_task_execution_phases : "organisation_id"

    %% Level 6 to Level 7 Relationships
    ec08_loan_assigns ||--o{ ec12_loan_payment_details : "loan_assign_id"
    ec11_loan_payments ||--o{ ec12_loan_payment_details : "loan_payment_id"
    ec10_loan_assign_schedules ||--o{ ec12_loan_payment_details : "loan_schedule_id"
    ec09_loan_assign_particulars ||--o{ ec12_loan_payment_details : "loan_assign_particular_id"

    %% Cross-module workflow relationships
    wf05_task_executions ||--o{ ec15_thfund_master_dbs : "task_execution_id"
    wf05_task_executions ||--o{ ec16_shfund_member_master_dbs : "task_execution_id"
    wf05_task_executions ||--o{ ec17_shfund_bank_master_dbs : "task_execution_id"
    wf05_task_executions ||--o{ ec21_bank_loan_schemes : "task_execution_id"
    wf05_task_executions ||--o{ ec21_bank_loan_borroweds : "task_execution_id"
    wf05_task_executions ||--o{ ec15_thfund_transactions : "task_execution_id"
    wf05_task_executions ||--o{ ec23_bank_loan_payments : "task_execution_id"
```

## Key Relationship Types

### One-to-Many Relationships (||--o{)
- **Organization** to Financial Years, Members, Officials
- **Member** to Loan Requests, Fund Operations
- **Loan Scheme** to Loan Details and Assignments
- **Bank** to Bank Loan Schemes and Fund Operations

### One-to-One Relationships (||--||)
- **Loan Request** to Loan Assignment (business rule: one request = one assignment)

### Many-to-Many Relationships (through junction tables)
- **Roles** to **Operations** through `tb02_role_operations`
- **Task Events** to **Role Permissions** through `wf09_task_event_role_permissions`

## Business Flow Patterns

1. **Member Loan Flow**: Member → Loan Request → Loan Assignment → Payment Schedules → Payments → Payment Details
2. **Fund Management Flow**: Member → Fund Master → Fund Transactions
3. **Bank Loan Flow**: Bank → Bank Loan Scheme → Bank Loan Borrowed → Bank Loan Payments
4. **Workflow Flow**: Task Category → Task Event → Task Execution → Execution Phases

## Critical Dependencies

- **Organisation Context**: Almost all business entities depend on `ec01_organisations`
- **User Authentication**: All operational activities link to `users` table
- **Financial Year**: Time-based operations reference `ec02_financial_years`
- **Member-Centric**: All financial operations ultimately trace to `ec04_members`
- **Workflow Integration**: Task executions link operational tables to workflow engine