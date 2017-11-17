import axios from 'axios';

console.log('test');

const getData = (url) => {
    return(
        axios({
            method: 'get',
            baseURL: 'http://localhost/api/api/v1.0/',
            url
        })
    )
}

const postData = (url, data) => {
    return(
        axios({
            method: 'post',
            baseURL: 'http://localhost/api/api/v1.0/',
            url,
            data
        })
    )
}

const putData = (url, data) => {
    return(
        axios({
            method: 'put',
            baseURL: 'http://localhost/api/api/v1.0/',
            url,
            data
        })
    )
}

const deleteData = (url, data = null) => {
    return(
        axios({
            method: 'delete',
            baseURL: 'http://localhost/api/api/v1.0/',
            url,
            data
        })
    )
}

getData('a/b?d1=abc&d2=123')
.then(response => {
    console.log(response)
})

postData('a/b', {a: 1, b: 1})
.then(response => {
    console.log(response)
})

putData('a/b', {a: 1, b: 1})
.then(response => {
    console.log(response)
})

deleteData('a/b')
.then(response => {
    console.log(response)
})
