
Workflow Management


 









 
Browsing URL: 
-	http://202.51.187.235/roster/
Database Information:
-	Roster
-	Db_shooz
-	asterisk
Corn File Name:

−	http://202.51.187.235/wallboard/15-min-report.php
Using this script store 15 min interval data in temp_15_interval (db_shooz) table.













Admin Panel
Campaign:
Must enter unique campaign name duplicate name doesn’t acceptable for this system.
 

Schedule: 
The second step is make schedule in this operation we need to be make schedule weekly. Collect the user from vicidial_users table and auto assign off day. Off day set 2 days for part time and 1 day for full time. For full time user the total collected user divide by 7 for select the number of user in each day. For part time user get the off day divided into 6 days of each week. But one day in each week all the user assign for work.
 

After make the schedule weekly then the next step is assign the users campaign wise. 
 
One user for assigned only one campaign. Also you can be relished all users select the remove campaign and click the add assign button. 
 

Shift: 
For shift creating provide the shift name and select the start and end time. In our system all the time related issue convert as 15 min. So at this stage total time duration divided as 15 min interval.
 
 After providing the required data you must select the work flow of your shift. You can be provide short break. It can be one work slot(15min) or its will be more then one slot. But for long break (30 min) you must select number of pairs its will be 2,4,6 or any kind of even number. 
 

All validation will be created form font end validation. All operation make using json data type. After completing creation you can be edit the work flow.

Forecasting:
For data calculation we use last 7 day data report. Like if we select Sunday then we calculate the last 7 Sunday data report. All calculations are performed using the Erlang formula. Providing the all input data click the calculate button after completing the calculation then click the save button. Similarly make this operation for 7 days. For next time if system call rate increment or decrement then make similarly make the calculation and store the current prediction for making roster. This data mainly use for suggest to admin when he/she make the roster. 

Roster: 
For make roster we can be used two method for make roster. Those are individual day or weekly roster creation. For weekly roster open the weekly roster interface. 
 
Provide the number of agent for each day like for Monday you need 7 agent for handle your system similarly provide for other day. You must provide the number of agent less then or equal as available agent. Click Set Roster button then system select the available user randomly and assign the selected shift. 
Similarly different kind of shift assign for different users. If the shift as like night shift the system suggest the user who sweet able for night shift. 

For individual day shift creation admin can be select users manually. After providing the input data our system data suggest the required data for each 15 min. This operation make roster for only one day.
  

Roster Manage:
 
After create the roster for 7 days or 1 day if admin find any wrong then admin can be delete the last created roster as a group. If the admin click the delete button then it delete the group. After delete the roster admin make its aging. 
Adherence Report:
In this report admin can be verified her agent working time for each 15 min. 
 
Users:
In this interface admin can be active the user or deactivate the user. This user visualization from visidial_users table. 
  
 

Reports:
Weekly Forecasting Report – In this report the visualization of Erlang calculation. This report helps for suggest to the number of needed users for each 15 min of a week.  
 

Slot Wise Report – In this report admin can be search each date and many slot at a time. The search result provide the needed agent for the selected date and slot.
 

Exchange Report – In this report admin can be view the shift exchange report and the off day exchange report. 
 

Off Day Report – This report provide the day off and work day at individual date. And admin can be change the work status as like admin can be change the off day and also can be change the working day to off day.

 




Agent Panel
Agent can be login using his/her agent panel user and password. 
Users Roster view interface:
After successfully login user can be search the roster. User only can be view the roster. 
 

Exchange Request: 
 
After search agent generate an exchange request. The request can be shift exchange request or its will be day of request. Agent search select the specific date and click the swap button and select the request type (Shift/Of Day).Select the request type then select the other user who already assign other shift and similar campaign. After sending the request the other side user accept or reject the exchange request. 
 

After sending the request the selected user login can be received the request if its sweet able for this user then accept the request or can be reject. Similarly all of the user can be send request for day off is the roster is created for long term. 
 

Weekly schedule report:
 


