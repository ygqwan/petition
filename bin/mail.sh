#!/bin/bash
mysql_bin="mysql -S /home/luotonglong/mysqlhome/var/mysql.sock -uoyl -p123456 firstblood_petition"

email_data=email.data
function fetch_data() {
    $mysql_bin -e "select * from email_to_send where sent_status = 0" 2>/dev/null > $email_data
}

function change_status() {
    $mysql_bin -e "update email_to_send set sent_status=1 where id=$1"
}

function send_email() {
    i=0
    cat $email_data | while read oneline
    do
        if (( $i == 0 ));then
            let "i=i+1"
            continue
        fi
        id=`echo $oneline | awk '{print $1}'`
        title=`echo $oneline | awk '{print $3}'`
        desc=`echo $oneline | awk '{print $4}'`
        to=`echo $oneline | awk '{print $5}'`
        cc=`echo $oneline | awk '{print $6}'`
        let "i=i+1"
        if python /usr/local/domob/prog.d/quaked-0.6.1/bin/sendemail.py -H 10.0.0.206 --port 10911 -e $to  -t "$title" -c "$desc" -s '请愿系统' -n '请愿系统';then
            change_status $id
        fi

    done
}
while :
do
    fetch_data

    send_email

    sleep 1m
done
