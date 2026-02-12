# CIPMS Module & Feature Documentation (Modular Version)

## 1. Core Project Module

This module manages project initialization and the Work Breakdown Structure (WBS) planning.

### Key Features:

- **Dual-Create Project Integration**: Features for creating projects in the local database while simultaneously triggering the Autodesk API to create a Project in ACC (to be implemented in the final phase).
- **Smart Project Coding**:
    - **Type Selection**: Internal or External.
    - **Code Generation**: Automated code generation based on contract numbers (e.g., `2026-INT-001`).
- **Sector & Service Metadata**:
    - **Technical Service**: Options for **Engineering** or **BIM**.
    - **Project Sector**: Options for Building, Water Resources, Infrastructure, or Energy.
- **WBS Hierarchy & Schedule Planning**:
    - **Team Leader Input**: Team Leaders have full access to input and manage schedule planning.
    - **Unlimited WBS Code**: Hierarchical structure without level limits (1, 1.1, 1.1.1, etc.).
    - **Auto-Nested Logic**: Codes automatically link to the parent level if the level 1 code is already in use.
    - **ACC File Auto-Sync**: Task filenames are automatically retrieved from files attached via ACC integration (final phase).
    - **Weight & Dates**: Manual input for task weights, start dates, and end dates.

---

## 2. Field Operations Module

Focuses on daily data entry discipline and BIM synchronization.

### Key Features:

- **Strict Daily Log**:
    - **System Time Enforcement**: All timestamps use the server clock (WIB) to prevent user manipulation.
    - **Anti-Backdate System**: Users are restricted from entering logs for past dates directly.
- **Backdate Approval Workflow**:
    - Requests for past-date entries must be submitted via a form requiring **Manager Approval**.
- **BIM Workspace**:
    - 3D Viewer integration to visualize Revit/IFC models corresponding to the assigned tasks.

---

## 3. Reporting & Analytics Module

Monitoring project health through visual data.

### Key Features:

- **Dynamic S-Curve Filtering**:
    - Users can toggle S-Curve visualizations between **Weekly** or **Monthly** periods.
- **Progress Tracking**:
    - **Total Progress**: Cumulative project progress based on weighted task rollups.
    - **Weight Coefficients**: Weight calculations influenced by project category coefficients for data accuracy.
- **Project Dashboard**:
    - Displays real-time S-Curve charts.
    - **Project Member Widget** to view all personnel assigned to the specific project.

---

## 4. Admin & Configuration Module

Centralized system control and authorization.

### Key Features:

- **RBAC (Role-Based Access Control)**: Permission management for Superadmin, Manager, Team Leader, and Employee roles.
- **System Settings**: Global configurations such as the **Auto-Checkout** schedule.
