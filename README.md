# lbaw2353 - ProjPlanner

Introducing ProjPlanner, a comprehensive project management solution designed to streamline and enhance your project coordination experience. ProjPlanner empowers users to efficiently organize and execute their projects through a user-friendly interface. Users have the flexibility to create projects, invite team members, and delegate tasks seamlessly. The platform goes beyond basic task management by allowing users to categorize tasks with tags and plan them on a visual Kanban board for a more intuitive workflow.

ProjPlanner also prioritizes time management by enabling users to set deadlines for both overall projects and individual tasks. This feature ensures that teams stay on track and meet important milestones. Additionally, users can conveniently upload and share files within the platform, promoting collaboration and centralizing project-related resources. On the administrative side, ProjPlanner provides powerful tools for system management. Admins have the authority to create and manage user accounts, as well as address user-related issues such as blocking and unblocking users. The system also supports a fair appeal process for blocked users who believe they have been unjustly restricted, fostering a transparent and accountable project management environment. With its robust features and user-centric design, ProjPlanner is your go-to solution for efficient and collaborative project management.

### 1. Installation

**Link to the final version**: 
  
**Docker Command to start the image**: 
```bash
docker run -it -p 8000:80 --name=lbaw2353 -e DB_DATABASE="lbaw2353" -e DB_SCHEMA="lbaw2353" -e DB_USERNAME="lbaw2353" -e DB_PASSWORD="lROapSCU" git.fe.up.pt:5050/lbaw/lbaw2324/lbaw2353
```

### 2. Usage

> URL to the product: http://lbaw2353.lbaw.fe.up.pt  

#### 2.1. Administration Credentials

To access the administration tools, you should first be logged in at the:

> Login URL: http://lbaw2353.lbaw.fe.up.pt/login

And use the following Credentials:

| Email    | Password  |
| -------- | -------- |
| admin@admin.com    | passadmin |


Then you will be redirected to the:

> Administration URL: http://lbaw2353.lbaw.fe.up.pt/admin  


#### 2.2. User Credentials

Users can be in several situations using the product. To access some of them, you should log in to the

> Login URL: http://lbaw2353.lbaw.fe.up.pt/login

Using the following credentials: 

| Type          | Username  | Password |
| ------------- | --------- | -------- |
| Normal User on product | auser2@user.com | password123
| User Coordinating 3 projects | user@user.com    | password123 |
| Banned User that can only see the appeal for unban page   | auser@user.com    | password123 | 

# Project theme: project management
- Daniel dos Santos Ferreira (up202108771@edu.fe.up.pt)
- Francisco Miguel Correia Mariano Pinheiro Cardoso (up202108793@edu.fe.up.pt)
- José António Pereira Martins (up202108794@edu.fe.up.pt)
- Tomás Alexandre Torres Pereira (up202108845@edu.fe.up.pt).
