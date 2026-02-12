# CIPMS Database Schema (Modular)

## 1. `projects` Table

Primary project data and ACC integration identifiers.
| Column | Type | Description |
| :--- | :--- | :--- |
| `id` | BigInt (PK) | |
| `project_type` | Enum | Internal, External |
| `contract_number` | String | Unique contract identifier |
| `project_code` | String | Auto-generated project code |
| `name` | String | Project name |
| `technical_service` | Enum | Engineering, BIM |
| `sector` | Enum | Building, Water Resources, Infrastructure, Energy |
| `acc_project_id` | String | Project ID from Autodesk ACC (nullable) |
| `status` | String | Active, Completed, On-Hold |

## 2. `tasks` Table (WBS)

Supports unlimited hierarchy and progress calculations.
| Column | Type | Description |
| :--- | :--- | :--- |
| `id` | BigInt (PK) | |
| `project_id` | Foreign Key | Relation to `projects` |
| `parent_id` | Foreign Key | Self-reference for WBS hierarchy |
| `wbs_code` | String | Hierarchical code (1, 1.1, etc.) |
| `name` | String | Task name |
| `acc_file_name` | String | Automatically retrieved from ACC |
| `weight` | Decimal | Task weight |
| `coefficient` | Decimal | Category multiplier |
| `start_date` | Date | |
| `end_date` | Date | |
| `total_progress` | Decimal | Cumulative progress (Default 0.00) |

## 3. `daily_logs` Table

Daily activity tracking with strict time controls.
| Column | Type | Description |
| :--- | :--- | :--- |
| `id` | BigInt (PK) | |
| `user_id` | Foreign Key | Relation to `users` |
| `task_id` | Foreign Key | Relation to `tasks` |
| `log_date` | Date | Follows System Date |
| `clock_in` | Time | Start time (System Time) |
| `clock_out` | Time | End time (System Time) |
| `progress_increment`| Decimal | Daily progress addition |
| `is_backdate` | Boolean | Default False |
| `approval_status` | Enum | Pending, Approved, Rejected |

## 4. `project_user` Table (Pivot)

Defines project membership and specific roles.
| Column | Type | Description |
| :--- | :--- | :--- |
| `project_id` | Foreign Key | |
| `user_id` | Foreign Key | |
| `role_in_project` | String | Manager, Team Leader, Member |

---

## 5. Mathematical Formulas & Logic

### A. Progress Rollup Formula (Weighted Average)

To calculate progress for a Parent Task at any WBS level:
$$P_{parent} = \frac{\sum (P_{child} \times W_{child})}{100}$$
_Where $P$ = Progress Percentage and $W$ = Weight._

### B. Weight Validation Formula

To ensure the integrity of the WBS weight distribution:
$$\sum W_{siblings} = W_{parent}$$
_(For root-level tasks, the total sum must be 100%)._

### C. S-Curve Deviation (Variance)

To calculate the gap between planned and actual progress:
$$Variance = Actual\_Progress - Planned\_Progress$$

### D. Coefficient Adjusted Weight

To calculate the effective weight based on project category coefficients:
$$W_{effective} = W_{base} \times C_{sector}$$
