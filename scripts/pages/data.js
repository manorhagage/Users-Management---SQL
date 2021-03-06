// prevent from unauthorized user to get in
if(!sessionStorage.getItem('online'))
{
    window.location = './';
}

// values to execute fetch request
let json = {'action': 'get' , 'table': 'users' };
let action = json['action'];
delete json.action;

// commit fetch request
let promise = establishRequest(action,json);

// values to print in the table
let fieldsName = ['Name', 'Created-at', 'Email' ];
let fieldsToPrint = ['username', 'usercreatedat', 'useremail'];

// promise to table
printPromiseToTable( promise, fieldsName, fieldsToPrint, '.users' );

// reload every 3 sec
setInterval( () => { 
    promise = establishRequest(action,json);
    printPromiseToTable( promise, fieldsName, fieldsToPrint, '.users' ); 
}, 3000 );

// logout user
_( '#logout' ).addEventListener('click', e =>
{
    sessionStorage.clear();
    window.location = './';
});