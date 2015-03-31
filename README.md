# mikrotik-address-lists

```
/system script add name=update-lists source={
 :log info "Address lists update started";
 /tool fetch url="/lists.php?id=aws&name=dst-amazonaws" dst-path=aws.txt;
 /tool fetch url="/lists.php?id=facebook" dst-path=fb.txt;
 /tool fetch url="/lists.php?id=google" dst-path=google.txt; 
 /tool fetch url="/lists.php?id=bso" dst-path=bso.txt; 
 /tool fetch url="/lists.php?id=netflix" dst-path=netflix.txt; 
 /tool fetch url="/lists.php?id=proceau" dst-path=proceau.txt; 
 /import file-name=aws.txt; /file remove aws.txt; 
 /import file-name=fb.txt; /file remove fb.txt;
 /import file-name=google.txt; /file remove google.txt;
 /import file-name=bso.txt; /file remove bso.txt;
 /import file-name=netflix.txt; /file remove proceau.txt;
 /import file-name=proceau.txt; /file remove netflix.txt;
 :log info "Address lists updated";
}

```
