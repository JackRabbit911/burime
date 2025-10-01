import type { MessageAction } from "store/messages/types";

import Wrapper from "reused/Wrapper";

type Props = {
  onClose: () => void;
  actions?: MessageAction[];
};

const Actions = ({ actions, onClose }: Props) =>
  !actions?.length ?
  null : (
    <Wrapper>
      {actions.map(
        ({ label, onClick }, key) => (
          <button
            key={key}
            className="btn"
            onClick={onClick || onClose}
          >
            {label}
          </button>
        )
      )}
    </Wrapper>
  );

export default Actions;
