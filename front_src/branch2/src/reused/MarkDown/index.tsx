type Props = {
  text?: string;
};

const MarkDown = ({text = '' }: Props) => (
  <div
    className="markdown"
    dangerouslySetInnerHTML={{ __html: text }}
  />
);

export default MarkDown;
