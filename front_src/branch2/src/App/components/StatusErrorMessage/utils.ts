const reasons: [number, string][] = [
    [401, 'Unauthorized'],
    [403, 'Forbidden'],
    [404, 'Not found'],
    [500, 'Internal server error'],
    [503, 'Service Unavailable'],
];

export const getReason = (status: number) => {
    const description = reasons.find(
        (reason) => reason[0] === status
    )?.[1] || 'Unknown error';

    return `${status} | ${description}`;
};
