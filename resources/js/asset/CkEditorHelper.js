

export function Image_picker(content, limit = 3) {
    const parser = new DOMParser().parseFromString(content, 'text/html')
    const images = parser.querySelectorAll('img')
    try {
        if (images.length > limit) {
            throw new Error(`You can not upload more then ${limit} image`);
        }

        if (images.length > 0) {
            let sources = []
            images.forEach(element => sources.push(element.src))
            return sources
        }
        return null;

    } catch (error) {
        return error
    }
}
export function Filter_length(content, limit = 1500) {
    if (content.length > 1500) {
        return new Error(`question must be completed under ${limit} charecters`)
    }
    return true
}

export function Inject_images(images, name = 'images', forum) {
    let sources = JSON.stringify(images)
    let srclist = document.createElement('input')
    srclist.type = 'hidden'
    srclist.name = name
    srclist.value = sources
    if (forum instanceof Element || forum instanceof Document) {
        forum.prepend(srclist)
    } else if (typeof forum == 'string') {
        const forum_element = document.getElementById(forum)
        return forum_element ? forum_element.prepend(srclist) : new Error('Forum element does not exists please provide a valid id')
    }
    return true;
}