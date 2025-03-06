export function isImage(file) {
    return file.mime.startsWith('image/')
}
export function isVideo(file) {
    return file.mime.startsWith('video/')
}
export function isAudio(file){
    return file.mime.startsWith('audio/')
}
export function isPdf(file) {
    return file.mime.startsWith('application/pdf')
}
export function isTxt(file) {
    return file.mime.startsWith('text/')
}

export function isExl(file){

    return [
        'application/vnd.ms-excel',
    ].includes(file.mime)
}

export function isWord(file){
    return [
        'application/x-abiword',
        'application/msword',	
    ].includes(file.mime)
}

export function isZip(file){
    return [
        'application/zip',
        'application/x-bzip',
        'application/x-bzip2',
        'application/epub+zip',
        'application/x-7z-compressed',
    ].includes(file.mime)
}

