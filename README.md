# mikrotik-address-lists

```
/system script add name=update-lists source={
  /tool fetch url="/lists.php?id=aws&name=dst-amazonaws" dst-path=aws.txt; /import file-name=aws.txt;
  /tool fetch url="/lists.php?id=facebook" dst-path=fb.txt; /import file-name=fb.txt;
  /tool fetch url="/lists.php?id=google" dst-path=google.txt; /import file-name=google.txt;
  /tool fetch url="/lists.php?id=bso" dst-path=bso.txt; /import file-name=bso.txt;
  /tool fetch url="/lists.php?id=proceau" dst-path=proceau.txt; /import file-name=proceau.txt;
  /file remove aws.txt; 
  /file remove fb.txt; 
  /file remove google.txt; 
  /file remove bso.txt; 
  /file remove proceau.txt;
  :log info "Address lists updated";
}
```
