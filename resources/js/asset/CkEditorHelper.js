

export function Image_picker(content, limit = 3) {
    const parser = new DOMParser().parseFromString(content, 'text/html')
    const images = parser.querySelectorAll('img')
    if (images.length > limit) {
        e.preventDefault()
        return `You can not upload more then ${limit} image`;
    }

    if (images.length > 0) {
        let sources = {}
        images.forEach(element => {
            const { src } = element
            sources[Object.keys(sources).length + 1] = src
        })
        return sources
    }
    return null;
}
export function Filter_length(content, limit = 1500) {
    if (content.length > 1500) {
        e.preventDefault()
        return `question must be completed under ${limit} charecters`
    }
    return true
}

export function Inject_images(images, name = 'images', forum) {
    let sources = JSON.stringify(images)
    let srclist = document.createElement('input')
    srclist.type = 'hidden'
    srclist.name = name
    srclist.value = sources
    if (forum instanceof Element || forum instanceof HTMLDocument) {
        forum.prepend(srclist)
    } else if (typeof forum == 'string') {
        const fourm_element = document.getElementById(forum)
        return forum_element ? fourm_element.prepend(srclist) : 'dom element does not exists please provide a valid id'
    }
    return false;
}