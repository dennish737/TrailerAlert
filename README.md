# TrailerAlert
MITRU Monitoring Application

Snohomish County Department of Emergency Management (SnoDEM) has developed the MITRU trailer, 
which can be deployed to support Disasters, Emergency Management and Activities to provide power, 
communication and internet services anywhere in Snohomish County or neighboring in the state of 
Washington.

As part of the MITRU system, each unit has an onboard APRS tracking system, providing location, 
temperature and power status. Allowing anyone to view ant track the status of MITRU Trailer using 
the APRS tracking web page (aprs.fi) and the trailer id.

TrailerAlert is a software package, running on a small Ubuntu/LINX client (e.g. NUC or Raspberry Pi) that:

1.	Periodically scans the aprs.fi page for MITRU Data and update the status data (TrailerDataCapture.py)
2.	Applies a set of rules to the updated data to determine if any alerts need to be set and cleared (Rules.py)
3.	Send new Alert and Alert Cleared messages (alert.py)
4.	Provide a set of web pages to manage and view MITRU Data (<server>/trailers)
5.	
Each of the major application are executed from crontab via a shell script. There is one shell script
for each of the application
