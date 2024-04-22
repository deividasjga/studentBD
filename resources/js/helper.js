export const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};

export const formatDateDash = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-CA', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit'
    });
};
